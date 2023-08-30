<?php

namespace App\Http\Controllers\Classes;

use App\Http\Classes\Common;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\ServiceToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function Clue\StreamFilter\fun;
use function foo\func;

/**
 * Class AmoCrm
 * @package App\Http\Classes\Controllers
 */
class AmoCrm extends Controller
{
    public $cfg = [];

    public function __construct()
    {
        $this->cfg = [
            'url' => config('config.AMO_REDIRECT_URL')
        ];
    }

    public $pipeline_id = 1635712;

    public $states = ['anketa' => [
        0 => ['text' => 'Сделка создана(ручное создание)', 'amo_id' => 36808114],
        1 => ['text' => 'заполнена анкета', 'amo_id' => 24814567],
        2 => ['text' => 'АНКЕТА ОПЛАЧЕНА ( БОТ )', 'amo_id' => 37510732],
        3 => ['text' => 'Готова к назначению', 'amo_id' => 25089748],
        4 => ['text' => 'АНКЕТА У СТИЛИСТА', 'amo_id' => 24814573],
        5 => ['text' => 'подборка составлена', 'amo_id' => 24814576],
        6 => ['text' => 'подборка составлена', 'amo_id' => 24814576],
        7 => ['text' => 'Одежда готова к отправке', 'amo_id' => 34725598],
        8 => ['text' => 'Отгружена', 'amo_id' => 24838114],
        9 => ['text' => 'одежда у клиента', 'amo_id' => 24838120],
        10 => ['text' => 'ОС отправлена', 'amo_id' => 30956992],
        11 => ['text' => 'ОС отправлена', 'amo_id' => 35093551],
        12 => ['text' => '31352857', 'amo_id' => 31352857],
        13 => ['text' => 'Счет PUSH', 'amo_id' => 40474936],
        14 => ['text' => 'СДЕЛКА ЗАКРЫТА', 'amo_id' => 28226977],
        15 => ['text' => 'АНКЕТА У СТИЛИСТА (Уточнить запрос)', 'amo_id' => 24814573],
        16 => ['text' => 'АНКЕТА У СТИЛИСТА (Запросить замеры)', 'amo_id' => 24814573],
        17 => ['text' => 'АНКЕТА У СТИЛИСТА (Нет нужных размеров)', 'amo_id' => 24814573],
        18 => ['text' => 'АНКЕТА У СТИЛИСТА (Нет вещей на складе)', 'amo_id' => 24814573],
        19 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        20 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        21 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        22 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        23 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        24 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        25 => ['text' => 'Закрыто и не реализовано', 'amo_id' => 143],
        30 => ['text' => 'Доставлена до ПВЗ', 'amo_id' => 45677761],
    ]];

    /**
     * Получение токена
     * @param $auth_code
     * @return bool
     */
    public function get_token($auth_code = '')
    {


        if (!$service = ServiceToken::where('service', 'amo')->first())
            return false;

        if (isset($service->token) && now() < Carbon::parse($service->expired_at))
            return $service->token;

        $pars['body'] = [
            'client_id' => config('config.AMO_CLIENT_ID'),
            'client_secret' => config('config.AMO_CLIENT_SECRET'),
            'grant_type' => empty($auth_code) ? 'refresh_token' : 'authorization_code',
        ];

        if (empty($auth_code))
            $pars['body']['refresh_token'] = $service->refresh_token;
        else {
            $pars['body']['code'] = $auth_code;
            $pars['body']['redirect_uri'] = config('config.AMO_REDIRECT_URL');
        }

        $res = $this->request('get_token', $pars);

        if ($res === false) {
            return false;
        } else {
            $service->token = $res['access_token'];
            $service->refresh_token = $res['refresh_token'];
            $service->expired_at = now()->addHour(24);
            $service->save();

            return $service->token;
        }

    }

    /**
     * Получить сделку
     * @param $id
     * @return mixed
     */
    function get_lead($id)
    {
        $res = $this->request('get_lead', ['id' => $id]);
        return $res;
    }

    function get_pipelines()
    {
        $res = $this->request('get_pipelines');
        return $res;
    }

    function get_contact($id, $return_httpcode = false)
    {
        $res = $this->request('get_contact', ['id' => $id], $return_httpcode);

        return $res;
    }

    function search_contact($query, $return_httpcode = false)
    {
        $res = $this->request('search_contact', ['get' => 'query=' . urlencode($query)], $return_httpcode);

        return $res;
    }

    function add_contact($pars = [], $return_httpcode = false)
    {
        // $pars: name, phone, email
        if (isset($pars['name'])) {
            $body['name'] = $pars['name'];
        }

        if (isset($pars['phone']))
            $body['custom_fields_values'][] = ['field_id' => 485781, 'values' => [['value' => Common::format_phone($pars['phone']), 'enum_code' => 'MOB']]];
        if (isset($pars['email']))
            $body['custom_fields_values'][] = ['field_id' => 485783, 'values' => [['value' => $pars['email'], 'enum_code' => 'PRIV']]];
        if (!empty($pars['instagramm']))
            $body['custom_fields_values'][] = ['field_id' => 486137, 'values' => [['value' => $pars['instagramm']]]];

        $pars_request['body'] = [$body];
        $res = $this->request('add_contact', $pars_request, $return_httpcode);

        return $res;
    }

    function link_lead_contact($id_lead, $id_contact, $return_httpcode = false)
    {
        $pars_request['id_lead'] = $id_lead;

        $body['to_entity_id'] = (int)$id_contact;
        $body['to_entity_type'] = 'contacts';

        $pars_request['body'] = [$body];

        $res = $this->request('link_lead_contact', $pars_request, $return_httpcode);

        return $res;
    }

    function add_lead($pars = [], $return_httpcode = false)
    {
        // $pars: name, state, anketa_view, phone, email, date_delivery
        if (isset($pars['name'])) {
            $body['name'] = $pars['name'];

            if (isset($pars['contact_id'])) {
                $body['contacts_id'] = (int)$pars['contact_id'];
            }

            if ($pars['name'] === false) $pars['name'] = '';
            $body['custom_fields_values'][] = ['field_id' => 684339, 'values' => [['value' => $pars['name']]]];
        }

        if (isset($pars['state']))
            $body['status_id'] = $this->states['anketa'][$pars['state']]['amo_id'];

        if (isset($pars['anketa_view']))
            $body['custom_fields_values'][] = ['field_id' => 684259, 'values' => [['value' => $pars['anketa_view']]]];
        if (isset($pars['phone']))
            $body['custom_fields_values'][] = ['field_id' => 675953, 'values' => [['value' => Common::format_phone($pars['phone'])]]];
        if (isset($pars['email']))
            $body['custom_fields_values'][] = ['field_id' => 683903, 'values' => [['value' => $pars['email']]]];
        if (isset($pars['address_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 684415, 'values' => [['value' => $pars['address_delivery']]]];
        if (isset($pars['address_back']))
            $body['custom_fields_values'][] = ['field_id' => 684541, 'values' => [['value' => $pars['address_back']]]];
        if (isset($pars['time_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 665973, 'values' => [['value' => $pars['time_delivery']]]];
        if (isset($pars['time_back']))
            $body['custom_fields_values'][] = ['field_id' => 684257, 'values' => [['value' => $pars['time_back']]]];
        if (isset($pars['date_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 684653, 'values' => [['value' => strtotime($pars['date_delivery'])]]];
        if (isset($pars['instagramm']))
            $body['custom_fields_values'][] = ['field_id' => 486137, 'values' => [['value' => strtotime($pars['instagramm'])]]];

        if (isset($pars['delivery_comment']))
            $body['custom_fields_values'][] = ['field_id' => 684539, 'values' => [['value' => $pars['delivery_comment']]]];

        if (isset($pars['delivery_pvz']))
            $body['custom_fields_values'][] = ['field_id' => 773895, 'values' => [['value' => $pars['delivery_pvz']]]];

        if (isset($pars['comment_d']))
            $body['custom_fields_values'][] = ['field_id' => 684539, 'values' => [['value' => $pars['comment_d']]]];

        if (isset($pars['tags']) && !is_array($pars['tags']))
            $pars['tags'] = [$pars['tags']];

        if (isset($pars['date_delivery']))
            $pars['tags'][] = $pars['date_delivery'];

        if (isset($pars['is_new']))
            if($pars['is_new'] === 0) $pars['tags'][] = 'ПОВТОР';

        if (isset($pars['coupon']))
            $body['custom_fields_values'][] = ['field_id' => 770687, 'values' => [['value' => $pars['coupon']]]];

        if (isset($pars['source']))
            $body['custom_fields_values'][] = ['field_id' => 679457, 'values' => [['value' => $pars['source']]]];

        //доп поля
        //цель подборки
        if (isset($pars['whatPurpose']))
            $body['custom_fields_values'][] = ['field_id' => 684885, 'values' => [['value' => $pars['whatPurpose']]]];

        //рост
        if (isset($pars['bioHeight']))
            $body['custom_fields_values'][] = ['field_id' => 670781, 'values' => [['value' => $pars['bioHeight']]]];

        //вес
        if (isset($pars['bioWeight']))
            $body['custom_fields_values'][] = ['field_id' => 670785, 'values' => [['value' => $pars['bioWeight']]]];

        //размер верха
        if (isset($pars['sizeTop']))
            $body['custom_fields_values'][] = ['field_id' => 670835, 'values' => [['value' => $pars['sizeTop']]]];

        //размер низа
        if (isset($pars['sizeBottom']))
            $body['custom_fields_values'][] = ['field_id' => 670787, 'values' => [['value' => $pars['sizeBottom']]]];

        //Бюджет блуза / рубашка
        if (isset($pars['howMuchToSpendOnBlouseShirt']))
            $body['custom_fields_values'][] = ['field_id' => 781629 , 'values' => [['value' => $pars['howMuchToSpendOnBlouseShirt']]]];

        //Бюджет пуловеры / свитеры
        if (isset($pars['howMuchToSpendOnSweaterJumperPullover']))
            $body['custom_fields_values'][] = ['field_id' =>  781631, 'values' => [['value' => $pars['howMuchToSpendOnSweaterJumperPullover']]]];

        //Бюджет платья / сарафаны
        if (isset($pars['howMuchToSpendOnDressesSundresses']))
            $body['custom_fields_values'][] = ['field_id' =>  781633, 'values' => [['value' => $pars['howMuchToSpendOnDressesSundresses']]]];

        //Бюджет на жакеты / пиджаки
        if (isset($pars['howMuchToSpendOnJacket']))
            $body['custom_fields_values'][] = ['field_id' =>  781635, 'values' => [['value' => $pars['howMuchToSpendOnJacket']]]];

        //Бюджет на сумки
        if (isset($pars['howMuchToSpendOnBags']))
            $body['custom_fields_values'][] = ['field_id' =>  781637, 'values' => [['value' => $pars['howMuchToSpendOnBags']]]];

        //Бюджет на юбки / брюки / джинсы
        if (isset($pars['howMuchToSpendOnJeansTrousersSkirts']))
            $body['custom_fields_values'][] = ['field_id' =>  781639, 'values' => [['value' => $pars['howMuchToSpendOnJeansTrousersSkirts']]]];

        //Бюджет на серьги / колье / браслеты
        if (isset($pars['howMuchToSpendOnEarringsNecklacesBracelets']))
            $body['custom_fields_values'][] = ['field_id' =>  781641, 'values' => [['value' => $pars['howMuchToSpendOnEarringsNecklacesBracelets']]]];

        //Бюджет на ремни / шарфы / платки
        if (isset($pars['howMuchToSpendOnBeltsScarvesShawls']))
            $body['custom_fields_values'][] = ['field_id' =>  781643, 'values' => [['value' => $pars['howMuchToSpendOnBeltsScarvesShawls']]]];

        if (isset($pars['tags'])) {
            foreach ($pars['tags'] as $tg) {
                $arrTags[] = ['name' => $tg];
            }

            $body['_embedded'] = ['tags' => $arrTags];
        }

        $pars_request['body'] = [$body];

        $res = $this->request('add_lead', $pars_request, $return_httpcode);
        return $res;
    }

    function update_lead($pars = [], $return_httpcode = false)
    {

        // $pars: id, state, tags (массив или текст), paid_price

        $body['id'] = intval($pars['id']);

        // $pars: name, state, anketa_view, phone, email, date_delivery
        if (isset($pars['name'])) {
            $body['name'] = $pars['name'];

            if ($pars['name'] === false) $pars['name'] = '';
            $body['custom_fields_values'][] = ['field_id' => 684339, 'values' => [['value' => $pars['name']]]];
        }

        if (isset($pars['state']))
            $body['status_id'] = $this->states['anketa'][$pars['state']]['amo_id'];

        if (isset($pars['price']))
            $body['price'] = $pars['price'];

        if (isset($pars['tags']) && !is_array($pars['tags']))
            $pars['tags'] = [$pars['tags']];

        if (isset($pars['tags'])) {
            foreach ($pars['tags'] as $tg) {
                $arrTags[] = ['name' => $tg];
            }

            $body['_embedded'] = ['tags' => $arrTags];
        }

        if (isset($pars['amo_id']))
            $body['custom_fields_values'][] = ['field_id' => 590427, 'values' => [['value' => $pars['amo_id']]]];

        if (isset($pars['paid_price']))
            $body['custom_fields_values'][] = ['field_id' => 683905, 'values' => [['value' => $pars['paid_price']]]];
        if (isset($pars['coupon']))
            $body['custom_fields_values'][] = ['field_id' => 770687, 'values' => [['value' => $pars['coupon']]]];
        if (isset($pars['address_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 684415, 'values' => [['value' => $pars['address_delivery']]]];
        if (isset($pars['address_back']))
            $body['custom_fields_values'][] = ['field_id' => 684541, 'values' => [['value' => $pars['address_back']]]];
        if (isset($pars['time_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 665973, 'values' => [['value' => $pars['time_delivery']]]];
        if (isset($pars['time_back']))
            $body['custom_fields_values'][] = ['field_id' => 684257, 'values' => [['value' => $pars['time_back']]]];
        if (isset($pars['date_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 684653, 'values' => [['value' => strtotime($pars['date_delivery'])]]];

        if (isset($pars['date_back']))
            $body['custom_fields_values'][] = ['field_id' => 684255, 'values' => [['value' => strtotime($pars['date_back'])]]];

        if (isset($pars['comment_d']))
            $body['custom_fields_values'][] = ['field_id' => 684539, 'values' => [['value' => $pars['comment_d']]]];

        if (isset($pars['stylist']))
            $body['custom_fields_values'][] = ['field_id' => 673855, 'values' => [['value' => $pars['stylist']]]];

        if (isset($pars['partner']))
            $body['custom_fields_values'][] = ['field_id' => 765405, 'values' => [['value' => $pars['partner']]]];

        if (isset($pars['delivery_pvz']))
            $body['custom_fields_values'][] = ['field_id' => 773895, 'values' => [['value' => $pars['delivery_pvz']]]];

        if (isset($pars['delivery_comment']))
            $body['custom_fields_values'][] = ['field_id' => 684539, 'values' => [['value' => $pars['delivery_comment']]]];

        if (isset($pars['paid_price']))
            $body['custom_fields_values'][] = ['field_id' => 683905, 'values' => [['value' => $pars['paid_price']]]];

        if (isset($pars['nps']))
            $body['custom_fields_values'][] = ['field_id' => 778169, 'values' => [['value' => $pars['nps']]]];

        if (isset($pars['next_delivery']))
            $body['custom_fields_values'][] = ['field_id' => 773821, 'values' => [['value' => $pars['next_delivery']]]];


        //доп поля
        //цель подборки
        if (isset($pars['whatPurpose']))
            $body['custom_fields_values'][] = ['field_id' => 684885, 'values' => [['value' => $pars['whatPurpose']]]];

        //рост
        if (isset($pars['bioHeight']))
            $body['custom_fields_values'][] = ['field_id' => 670781, 'values' => [['value' => $pars['bioHeight']]]];

        //вес
        if (isset($pars['bioWeight']))
            $body['custom_fields_values'][] = ['field_id' => 670785, 'values' => [['value' => $pars['bioWeight']]]];

        //размер верха
        if (isset($pars['sizeTop']))
            $body['custom_fields_values'][] = ['field_id' => 670835, 'values' => [['value' => $pars['sizeTop']]]];

        //размер низа
        if (isset($pars['sizeBottom']))
            $body['custom_fields_values'][] = ['field_id' => 670787, 'values' => [['value' => $pars['sizeBottom']]]];


        //Бюджет блуза / рубашка
        if (isset($pars['howMuchToSpendOnBlouseShirt']))
            $body['custom_fields_values'][] = ['field_id' => 781629 , 'values' => [['value' => $pars['howMuchToSpendOnBlouseShirt']]]];

        //Бюджет пуловеры / свитеры
        if (isset($pars['howMuchToSpendOnSweaterJumperPullover']))
            $body['custom_fields_values'][] = ['field_id' =>  781631, 'values' => [['value' => $pars['howMuchToSpendOnSweaterJumperPullover']]]];

        //Бюджет платья / сарафаны
        if (isset($pars['howMuchToSpendOnDressesSundresses']))
            $body['custom_fields_values'][] = ['field_id' =>  781633, 'values' => [['value' => $pars['howMuchToSpendOnDressesSundresses']]]];

        //Бюджет на жакеты / пиджаки
        if (isset($pars['howMuchToSpendOnJacket']))
            $body['custom_fields_values'][] = ['field_id' =>  781635, 'values' => [['value' => $pars['howMuchToSpendOnJacket']]]];

        //Бюджет на сумки
        if (isset($pars['howMuchToSpendOnBags']))
            $body['custom_fields_values'][] = ['field_id' =>  781637, 'values' => [['value' => $pars['howMuchToSpendOnBags']]]];

        //Бюджет на юбки / брюки / джинсы
        if (isset($pars['howMuchToSpendOnJeansTrousersSkirts']))
            $body['custom_fields_values'][] = ['field_id' =>  781639, 'values' => [['value' => $pars['howMuchToSpendOnJeansTrousersSkirts']]]];

        //Бюджет на серьги / колье / браслеты
        if (isset($pars['howMuchToSpendOnEarringsNecklacesBracelets']))
            $body['custom_fields_values'][] = ['field_id' =>  781641, 'values' => [['value' => $pars['howMuchToSpendOnEarringsNecklacesBracelets']]]];

        //Бюджет на ремни / шарфы / платки
        if (isset($pars['howMuchToSpendOnBeltsScarvesShawls']))
            $body['custom_fields_values'][] = ['field_id' =>  781643, 'values' => [['value' => $pars['howMuchToSpendOnBeltsScarvesShawls']]]];

        // Ссылка на повторную анкету с имперсонализацией
        if (isset($pars['impersonateLink']))
            $body['custom_fields_values'][] = ['field_id' => 670245, 'values' => [['value' => $pars['impersonateLink']]]];

        $pars_request['body'] = [$body];
        $res = $this->request('update_lead', $pars_request, $return_httpcode);

        return $res;
    }

    function update_contact(int $id, array $pars = [], $return_httpcode)
    {
        if (empty($id)) return "empty id";
        if (empty($pars['full_name']) && empty($pars['socialmedia_links'])) return "empty params";

        $body['id'] = $id;
        if (isset($pars['full_name']))
            $body['name'] = $pars['full_name'];

        if (isset($pars['socialmedia_links']))
            $body['custom_fields_values'][] = ['field_id' => 486137, 'values' => [['value' => $pars['socialmedia_links']]]];

        $pars_request['body'] = [$body];
        $res = $this->request('update_contact', $pars_request, $return_httpcode);

        return $res;
    }

    /** Логирование
     * @param $text
     * @param string $type
     */
    function log($text, $type = 'all')
    {
        switch ($type) {
            case 'all':
                $out = date('d.m.Y H:i:s') . ': ' . $text . PHP_EOL;
                Storage::disk('local')->append('logs/amo_all.txt', $out);
                echo $out;

                break;
            case 'error':
                $out = date('d.m.Y H:i:s') . ': ' . $text . PHP_EOL;
                Storage::disk('local')->append('logs/amo_errors.txt', $out);

                break;
            default:
                $out = date('d.m.Y H:i:s') . ': ' . $text . "\n\n";
                Storage::disk('local')->append('logs/' . $type . '.txt', $out);

                break;
        }
    }


    /**
     * Фильтрация сделки
     * @param $state_id
     * @param bool $return_httpcode
     * @return bool|mixed
     */
    function filter_leads($state_id, $return_httpcode = false)
    {


        $from = $filter_from = now()->subDays(300)->timestamp;
        $to = now()->timestamp;
        $date_filter = "&filter[updated_at][from]=$from&filter[updated_at][to]=$to";

        return $this->request('filter_leads', ['get' => "filter[statuses][0][pipeline_id]="
            . $this->pipeline_id . $date_filter . "&filter[statuses][0][status_id]=" . $state_id . "&limit=250"], $return_httpcode);
    }

    /**
     * Фильтрация сделки
     * @param $state_id
     * @param bool $return_httpcode
     * @return bool|mixed
     */
    function filter_leads_all($state_id)
    {

        $page = 1;
        $data = [];
        $limit = 250;
        $from = $filter_from = now()->subDays(300)->timestamp;
        $to = now()->timestamp;
        $date_filter = "&filter[updated_at][from]=$from&filter[updated_at][to]=$to";

        do {
            $tmp_data = $this->request('filter_leads', ['get' => "filter[statuses][0][pipeline_id]="
                . $this->pipeline_id . $date_filter . "&filter[statuses][0][status_id]=" . $state_id . "&limit=$limit&page=$page"]);

            $count = count($tmp_data['_embedded']['leads']);
            if ($count >= $limit)
                $page++;

            $data[] = array_merge($tmp_data, $data);

        } while ($count == $limit);

        return $data;
    }

    /**
     * Добавление тегов с сохранением предыдущих
     * @param $lead_id
     * @param $tag
     * @return bool|mixed
     */
    function add_tags($lead_id, array $tag)
    {

        $amo_updates['id'] = $lead_id;
        $lead = self::get_lead($lead_id);

        $tags = false;
        if (!empty($lead["_embedded"]["tags"])) {
            $tags = collect(json_decode(json_encode($lead["_embedded"]["tags"])));
        }

        // Если были установленные тэги
        if (is_object($tags) && $tag) {
            foreach ($tag as $tg)
                $tags->add(['name' => $tg]);

            $amo_updates["tags"] = $tags->pluck('name')->toArray();
        } else {
            $amo_updates["tags"] = $tag;
        }

        //dd($amo_updates);

        return self::update_lead($amo_updates);

    }


    /**
     * Добавление в АМО информации о купленных продуктах с тегами и метками
     * 1) Добавление тега в зависимости от суммы
     * 2) Добавление примечаний в custom field
     * @param $lead_id
     * @param $amount
     * @param $nps
     * @param $next_delivery
     * @return bool
     */
    function purchasedProducts($lead_id, $amount, $nps, $next_delivery = false)
    {

        $default_id = 447523;
        try {
            $summ_info = [
                ["id" => $default_id,    "summ" => 0, "comment" => "0 вещей"],
                ["id" => 475013,    "summ" => 1, "comment" => "<3000"],
                ["id" => 447457,    "summ" => 3001, "comment" => "1"],
                ["id" => 469503,    "summ" => 8000, "comment" => ">8000"],
            ];

            $delivery_match = [
                false => "",
                "half_year" => "Через полгода",
                "two_months" => "Через два месяца",
                "month" => "Через месяц",
                "week" => "Через неделю",
            ];

            $summ = collect($summ_info);
            $tag_id = $summ->whereBetween("summ", [0, $amount])->last()["id"] ?? $default_id;

            if (!$add_tag = $this->add_tags_ids($lead_id, $tag_id)) {
                Log::channel('amo')->info("ОС: Ошибка добавления тега при поступлении по сделке $lead_id");
                return false;
            }


            Log::channel('amo')->error("ОС: Добавлен тег по сделке: $lead_id " . json_encode($tag_id));

            $pars = [
                'id' => $lead_id,
                'price' => $amount,
                'nps' => $nps,
                'next_delivery' => $delivery_match[$next_delivery],
            ];

            if (!$update_lead = $this->update_lead($pars)) {
                Log::channel('amo')->error("ОС: Ошибка UPDATE по сделке $lead_id");
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::channel('amo')->error("ОС: Ошибка: $lead_id" . @json_encode($tag_id));
        }

    }

    /**
     * Запрос к AMO API
     * @param $method
     * @param array $pars
     * @param bool $return_httpcode
     * @return bool|mixed
     */
    function request($method, $pars = [], $return_httpcode = false)
    {

        $id = $pars['id'] ?? null;
        $lead_id = $pars['id_lead'] ?? null;

        $methods = [
            'get_lead' => ['GET', '/api/v4/leads/' . $id],
            'get_contact' => ['GET', '/api/v4/contacts/' . $id],
            'search_contact' => ['GET', '/api/v4/contacts'],
            'add_lead' => ['POST', '/api/v4/leads'],
            'add_contact' => ['POST', '/api/v4/contacts'],
            'update_contact' => ['PATCH', '/api/v4/contacts'],
            'link_lead_contact' => ['POST', '/api/v4/leads/' . $lead_id . '/link'],
            'update_lead' => ['PATCH', '/api/v4/leads'],
            'get_token' => ['POST', '/oauth2/access_token', true],
            'get_pipelines' => ['GET', '/api/v4/leads/pipelines'],
            'filter_leads' => ['GET', '/api/v4/leads'],
        ];


        if (!array_key_exists($method, $methods)) {
            $this->log('Unknown method ' . $method, 'error');
            return false;
        }


        $headers[] = 'Content-Type: application/json';

        if (empty($methods[$method][2]))
            $headers[] = 'Authorization: Bearer ' . $this->get_token();

        switch ($methods[$method][0]) {
            case 'GET':
                $get_data = '';
                if (!empty($pars['get']))
                    $get_data = '?' . $pars['get'];
                $post_data = '';
                break;

            case 'DELETE':
            case 'POST':
            case 'PUT':
            case 'PATCH':
                $get_data = '';
                $post_data = json_encode($pars['body']);

                break;
        }

        $opts = ['http' => ['method' => $methods[$method][0],
            'ignore_errors' => true,
            'header' => $headers,
            'content' => $post_data
        ]];

        $ctx = stream_context_create($opts);
        $url = config('config.AMO_URL') . $methods[$method][1] . $get_data;

        $resp = file_get_contents($url, false, $ctx);

        $ret = json_decode($resp, true);

        $ret['status'] = explode(' ', $http_response_header[0])[1];

        if ($ret['status'] == 400) { // validation-error
            $this->log('Post: ' . $post_data . "\n\nResp: " . $resp, 'error');

            if ($return_httpcode)
                return ['status' => false, 'code' => 400];

            return false;
        }

        if ($ret['status'] == 401) { // неавторизованный запрос
            $this->log($resp, 'error');

            if ($return_httpcode) return ['status' => false, 'code' => 401];
            return false;
        }

        return $ret;
    }

    /**
     * Получение списка сделок из AmoCrm, подходящих под условия статуса и тега
     * @param int $state_id - TODO: поменять на нормальную воронку
     * @param string $tag_search
     * @return bool
     */
    public function getAmoLeads($state_id = 41967916, $tag_search = "boxberry")
    {

        try {
            $res = $this->filter_leads($state_id);
            if (!empty($res['_embedded']['leads'])) {
                foreach ($res['_embedded']['leads'] as $k => $v) {
                    if (!empty($v['_embedded']['tags'])) {
                        foreach ($v['_embedded']['tags'] as $k2 => $item) {
                            if ($item['name'] == $tag_search) {
                                $data[] = [
                                    "id" => $v['id'],
                                    "tag" => $v['_embedded']['tags'],
                                ];
                            }
                        }
                    }
                }

                return $data ?? false;
            } else
                return false;
        } catch (\Exception $e) {
            Log::channel('boxberry')->error("Ошибка обработки сделок: " . $e);
        }
    }

    /**
     * Обновление статуса сделки с проверкой текущего состояния
     * @param array $pars
     * @return bool|mixed
     */
    public function updateLeadWithCheck($pars = [])
    {

        if (!$current_lead = self::get_lead($pars['id']))
            return false;

        if (empty($current_lead['status_id']))
            return false;

        if (empty($this->states['anketa'][$pars['state']]['amo_id']) || empty($this->states['anketa'][$pars['prev_state']]['amo_id']))
            return false;

        if(isset($current_lead['status_id'])) {
            if ($current_lead['status_id'] != $this->states['anketa'][$pars['prev_state']]['amo_id'])
                return false;
        }

        $result = self::update_lead($pars);

        return $result;


    }


    /**
     * TODO: Сделать управление UI или АМО уйдет?
     * Переброс сделок Из одной воронки в другую
     * @param int $from_amo_state_id
     * @param int $lk_state
     * @return int
     */
    public function transitDelivery($from_amo_state_id = 43203331, $lk_state = 8)
    {

        $leads = self::filter_leads_all($from_amo_state_id);
        $transit = [];

        foreach ($leads[0]['_embedded']['leads'] as $key => $lead) {
            $pars = [
                'id' => $lead['id'],
                'state' => $lk_state,
            ];

            if ($resp = self::update_lead($pars))
                $transit[] = $lead['id'];

        }
        return count($transit);
    }

    public function setClientAuthToken(int $amo_lead_id, string $auth_token){

        $pars = [
            'id' => $amo_lead_id,
            'impersonateLink' => route('admin-clients.auth.impersonate', $auth_token),
        ];
        return  self::update_lead($pars);
    }

    /**
     * Добавление тегов с сохранением предыдущих по ID тегов
     * @param $lead_id
     * @param $tag
     * @return bool|mixed
     */
    function add_tags_ids(int $lead_id, int $tag)
    {

        $lead = self::get_lead($lead_id);

        if (!empty($lead["_embedded"]["tags"])) {
            $tags = collect(json_decode(json_encode($lead["_embedded"]["tags"])));
            $tags->add((object)['id' => $tag]);

            $data = $tags->map(function($item) {
                return ["id" => $item->id];
            });
        }

        $body['id'] = $lead_id;
        $body['_embedded'] = ['tags' => $data->unique()->all()];
        $pars_request['body'] = [$body];

        return $this->request('update_lead', $pars_request,1);

    }
}
