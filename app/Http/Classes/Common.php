<?php

namespace App\Http\Classes;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\Catalog\Brand;
use App\Http\Models\Catalog\CategoriesTranslator;
use App\Http\Models\Catalog\Category;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Lead;
use App\Jobs\NoterNamesJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Ixudra\Curl\Facades\Curl;
use Mockery\Exception;
use App\Http\Classes\SMSRU;

/**
 * Класс дополнительных функций
 * Class Common
 * @package App\Http\Classes
 */
class Common
{

    /**
     * @param array $visibleButtons
     * @param bool $hiddenButtons
     * @return string
     */
    public static function actionMenu($visibleButtons = [], $hiddenButtons = false){

        $html = '';

        if ($visibleButtons){
            foreach ($visibleButtons as $k=>$v)
                $html .= $v;
        }

        if ($hiddenButtons) {
            $html .= '<div class="d-inline-flex ml-1">
            <a class="pr-1 dropdown-toggle ырщц-arrow text-primary" data-toggle="dropdown" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a><div class="dropdown-menu dropdown-menu-right" style="">';

            if (is_array($hiddenButtons))
                foreach ($hiddenButtons as $k=>$v)
                    $html .= $v;

            $html .=  '</div></div>';
        }



        return $html;
    }

    /**
     * Загрузка данных по ID AMOCRM ?
     * @param $order_id
     * @return bool|\Exception|Exception ...
     */
    public static function importByOrderId($order_id){

        try {
            $response = Curl::to(config('config.CAPSULA_IMPORT_URI'))
                ->withConnectTimeout(90)
                ->withTimeout(90)
                ->withData([
                    'act' => 'noter',
                    'amo_id' => $order_id
                ])
                ->asJson()
                //->enableDebug(storage_path() .'/app/api/anketa'.date('Y-m-d').'.log')
                ->get();

//            if (\request()->ip() == '109.252.35.145')
//                dd($response);

            if ($response && isset($response->result) && !empty(isset($response->items)))
                return $response ?? false;
            else{
                if (isset($response->text))
                    return false;

            }
                return false;

        }
        catch (Exception $e){
            return false;
        }

    }

    /**
     * Создаем категорию по наименованию или возвращаем ID
     * @param $name
     * @return
     */
    public static function getCategoryId($type){

        $cat = Category::firstOrCreate([
            'name' => $type
        ],[
            'name' => $type,
            'slug' => self::transliterate($type),
            'parent_id' => 1,
            'user_id' =>  auth()->guard('admin')->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $cat->id ?? false;
    }

    /**
     * Создаем Бренд по наименованию или возвращаем ID
     * @param $type
     * @return
     */
    public static function getBrandId($type){


        $brand = Brand::firstOrCreate([
            'name' => $type
        ],[
            'name' => $type,
            'slug' => self::transliterate($type),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $brand->id ?? false;
    }

    /**
     * @param $string
     * @return bool|false|mixed|string|string[]|null
     */
    public static function transliterate($string){
        $str = mb_strtolower($string, 'UTF-8');
        $leter_array = array(
            'a' => 'а',
            'b' => 'б',
            'v' => 'в',
            'g' => 'г',
            'd' => 'д',
            'e' => 'е,э',
            'jo' => 'ё',
            'zh' => 'ж',
            'z' => 'з',
            'i' => 'и,i',
            'j' => 'й',
            'k' => 'к',
            'l' => 'л',
            'm' => 'м',
            'n' => 'н',
            'o' => 'о',
            'p' => 'п',
            'r' => 'р',
            's' => 'с',
            't' => 'т',
            'u' => 'у',
            'f' => 'ф',
            'kh' => 'х',
            'ts' => 'ц',
            'ch' => 'ч',
            'sh' => 'ш',
            'shch' => 'щ',
            '' => 'ъ',
            'y' => 'ы',
            '' => 'ь',
            'yu' => 'ю',
            'ya' => 'я',
        );

        foreach ($leter_array as $leter => $kyr){
            $kyr = explode(',',$kyr);
            $str = str_replace($kyr, $leter, $str);
        }

        $str = preg_replace('/(\s|[^A-Za-z0-9-])+/', '-', $str);
        $str = trim($str,'-');

        return $str;
    }

    /**
     * Получение фотографий из API
     * @param string $uri
     * @return bool|string
     */
    public static function getImage(string $uri){

        try {
            ini_set('memory_limit', '256M');
            $path = $uri . '.jpg';
            if (!Storage::disk('public')->exists(($path))) {

                $width = 600;
                $height = 800;
                $img = Image::make(config('config.CAPSULA_IMPORT_URI_IMAGE').$uri);
                $img->backup();
                $img->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if ($img->width() == 600 && $img->height() >= 800)
                    $img->resizeCanvas(600, 800, 'center', false, 'ffffff')
                    ->encode('jpg',95);
                else{
                    $img->reset();
                    $img->resize(false, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->resizeCanvas(600, 800, 'center', false, 'ffffff')
                        ->encode('jpg',95);
                }

                $file = Storage::disk('public')->put($path, $img);
                return $path;
            }
            else{
                return $path;
            }
        }
        catch (\Exception $e){
            Log::channel('ms')->error($e);
            return false;
        }
    }

    /**
     * Форматирование телефона к виду 79******
     * @param $in
     * @return string|string[]|null
     */
    static function format_phone($in) {
        $ret = preg_replace("/[^0-9]/", '', $in);
        if($ret == '') return '';

        if ($ret[0] == '8')
            $ret[0] = '7';

        if (strlen($ret) == 10)
            $ret = '7'.$ret;

        if (strlen($ret) < 11)
            $ret = str_pad($ret, 11, '_');

        return $ret;
    }


    /**
     * Отправка сообщения
     * @return \Illuminate\Http\JsonResponse
     */
    static function smsSend(){

        $validator = Validator::make(request()->all(), [
            'phone' => 'required | max:11 | min:11',
            'msg' => 'required | string | max:255 | min:2',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $sms_ru = new SMSRU(config('config.SMSRU_API_KEY'));

        $post = (object) [
            "to" => trim(request()->input('phone')),
            "msg" => trim(request()->input('msg')),
            "from" => config('config.APP_NAME'),
        ];

        if ($sms_ru->send_one($post))
            return response()->json([
                "result" => true,
                "message" => "Сообщение отправлено"
            ]);

        return response()->json([
            "result" => false,
            "message" => "Сообщение не было отправлено!"
        ]);

    }

    /**
     * Получение токена по названию сервиса | Может пригодиться?
     * @param string $service
     * @return bool
     */
    static function getToken(string $service){

        if(!$service = ServiceToken::where('service',$service)->first())
            return false;

        if (!isset($service->token) || !isset($service->expired_at) || now() > Carbon::parse($service->expired_at))
            return false;

        return $service->token;

    }

    /**
     * Последний клиентский номер сделки
     * @param string $client_uuid
     * @return int
     */
    static function clintMaxNum(string $client_uuid){

        return (int) Lead::where('client_id',$client_uuid)->max('client_num') + 1;
    }


    /**
     * Проверка на существование Сделки с AMO ID и клиента
     * @param $amo_lead_id
     * @return bool
     */
    public static function checkAmoId($amo_lead_id){

        return true;
        if(Lead::where('amo_lead_id',$amo_lead_id)->whereNotNull('client_id')->count() > 0)
            return true;

        return false;

    }

    /**
     * Обновление имени продукта
     * @param Lead $lead
     * @return bool
     */
    public static function updateNoterProductNames(Lead $lead)
    {

        NoterNamesJob::dispatch($lead);
        return true;
    }

    /**
     * Получение ТОЛЬКО ЦИФР из строки
     * @param $string
     * @return string|string[]|null
     */
    public static function getDigits($string)
    {
        return preg_replace("/[^0-9]/", '', $string);
    }

    /**
     * Получение ТОЛЬКО ЦИФР из строки
     * @param $string
     * @return string|string[]|null
     */
    public static function updatePvzID($string)
    {
        $pvz_id = preg_replace("/[^0-9]/", '', $string);

        if (strlen($pvz_id) < 5)
            return $pvz_id . "1";

        return (string) $pvz_id;
    }

    public static function getUser($user_id){

        // TODO: перенсти валидацию?
        $validator = Validator::make(["user_id" => $user_id], [
            'user_id' => 'required | string | max:36',
        ], Message::messages());

        if ($validator->fails()) {
            return (object)[
                'result' => false,
                'error' => $validator->errors()
            ];
        }

        if (!$client = Client::whereUuid($user_id)->first()){
            return (object)[
                'result' => false,
                'error' => "User ID не найден"
            ];
        }

        return (object) [
            'model' => $client,
            'error' => false
        ];

    }

    /**
     * Создание auth token для старых клиентов по всей БД
     * @return bool
     */
    public static function genClientAuthToken(){

        Client::whereNull("auth_token")->chunk(1000, function ($clients) {
            foreach ($clients as $client) {
                $client->update([
                    "auth_token" => uuid_v4()
                ]);
            }
        });

        return true;
    }


    /**
     * Создание auth token для старых клиентов по всей БД
     * @return bool|array|integer
     */
    public static function bulkClientAuthTokenAmo($state_id = 14,$lead_uuid = false){

        $amo = new AmoCrm();
        $errors = [];
        $leads = Lead::with(['clients'])->where("state_id",$state_id)->whereNotNull("client_id");

        if ($lead_uuid)
            $leads = $leads->where("uuid",$lead_uuid);

        $leads = $leads
            //->groupBy("client_id")
            ->get();

        foreach ($leads as $lead) {

            if (!empty($lead->clients->auth_token)) {
                $pars = [
                    'id' => $lead->amo_lead_id,
                    'impersonateLink' => route('admin-clients.auth.impersonate', $lead->clients->auth_token),
                ];

                if (!$resp = $amo->update_lead($pars,1))
                    $errors[] = $lead['id'];

            }
        }

        return $errors ?? true;
    }

}