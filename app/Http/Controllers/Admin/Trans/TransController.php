<?php

namespace App\Http\Controllers\Admin\Trans;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Trans\OldAnketa;
use App\Http\Models\Admin\Trans\TransClient;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Catalog\Category;
use App\Http\Models\Common\Bonus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Str;

/**
 * Class MainController - роутинг
 * @package App\Http\Controllers\Admin
 */
class TransController extends Controller
{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function csvClients(){
        $old_clients = DB::table('old_clients')->limit(10)->get();

        $items = DB::select(DB::raw("select o.* from old_clients o left join clients c USING (phone) where c.uuid is null  limit 10 ;"));

        $i= 0;
        foreach ($items as $item ) {

            $item = collect($item);
            dd($item);
            $i++;
        }
    }

    /**
     * Перенос клиентов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        dd('Заблокировано, иначе слетят все клиенты');

        $i = 0;
            $items = OldAnketa::whereRaw('LENGTH(data) > 10')->chunk(500,function($items) {
            $i = 0;

                try {
                    foreach ($items as $item) {

                        $i++;

                        if (!empty($item->data['anketa']['question'][0]['answer']) && !empty($item->data['anketa']['question'][15]['answer'])) {
                            $i++;

                            $phone = !empty($item->data['anketa']['question'][15]['answer']) ? Common::format_phone($item->data['anketa']['question'][15]['answer']) : null;

                            if (!$client = Client::where('phone',$phone)->first()) {

                                do {
                                    $rand = mt_rand(1000, 99999999);
                                    if (Client::where('referal_code', $rand)->count() == 0)
                                        break;
                                } while (0);

                                $client = Client::create([
                                    'code' => @$item->code,
                                    'name' => self::upperFirst(trim(mb_strimwidth($item->data['anketa']['question'][0]['answer'], 0, 100))),
                                    'phone' => $phone,
                                    'email' => !empty($item->data['anketa']['question'][14]['answer']) ? trim($item->data['anketa']['question'][14]['answer']) : null,
                                    'comments' => @$item->data['anketa']['question'][0]['answer'],
                                    'socialmedia_links' => !empty($item->data['anketa']['question'][68]['answer']) ? trim($item->data['anketa']['question'][68]['answer']) : null,
                                    'referal_code' => $rand,
                                    'amo_client_id' => (int)@$item->amo_id > 0 ? (int)@$item->amo_id : null,
                                    'created_at' => @Carbon::parse($item->created)->format('Y-m-d H:i:s'),
                                    'updated_at' => @Carbon::parse($item->updated)->format('Y-m-d H:i:s'),
                                ]);

                                // Пишем анкету
                                $questionnary = new Questionnaire();
                                $questionnary->code = $item->code;
                                $questionnary->data = $item->data;
                                $questionnary->status = $item->status;
                                $questionnary->created_at = @Carbon::parse($item->created)->format('Y-m-d H:i:s');
                                $questionnary->updated_at = @Carbon::parse($item->updated)->format('Y-m-d H:i:s');
                                $questionnary->amount = $item->amount ?? null;

                                if ($item->amo_id < 0)
                                    $questionnary->amo_id = 0;
                                else
                                    $questionnary->amo_id = $item->amo_id;

                                $questionnary->client_uuid = $client->uuid;

                                $questionnary->save();

                                // Пишем Бонус-Реф код
                                self::setPromocode($client);
                            }
                        }

                    }
                } catch (\Exception $e) {
                    dd($e);
                }
            });

        dd($i);


        return view('admin.catalog.index', [
            'title' => 'Главная страница',
            'data' => []
        ]);
    }

    protected function upperFirst($str, $encoding = 'UTF8')
    {
        return
            mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
    }

    protected function copyClients(){

        $items =  DB::connection('prod')->table('clients')->get();
        dd($items);

    }

    protected function setPromocode(Client $client){

        $name = $client->name;
        $name = Str::slug($name);
        $name_arr = explode('-', $name);

        $promocode = '';

        $phone_part = substr($client->phone, -4);

        if(count($name_arr) == 1 || count($name_arr) == 2 || count($name_arr) > 3) {
            $promocode = $name_arr[0];
        } elseif(count($name_arr) == 3) {
            $promocode = $name_arr[1];
        }

        $promocode .= $phone_part;

        $check_promo = Bonus::where('promocode', $promocode)->first();

        $rand_str = '';

        if($check_promo) {
            $rand_number = rand(0,9);
            $promocode .= (string) $rand_number;
            //dump($promocode);
        }


        $bonuses = new Bonus();
        $bonuses->client_id = $client->uuid;
        $bonuses->promocode = $promocode;
        $bonuses->save();

        return true;
    }

    /**
     *
     */
    public function getAmoClient(){

        $amo = new AmoCrm();

        $items = FeedbackgeneralQuize::whereNull('client_uuid')->orderBy('created_at','desc')->limit(1000)->offset(6000)->get();

        $i = [];
        foreach ($items as $item) {
            $lead = $amo->get_lead($item->amo_id);
            $phone = '88888888888888';

            if (isset($lead['custom_fields_values'])) {
                foreach ($lead['custom_fields_values'] as $k => $v) {
                    if ($v['field_name'] == 'PHONE') {
                        $phone = preg_replace("/[^0-9]/", '', $v['values'][0]['value']);
                    }
                }

            }

            if ($client = Client::where('phone', 'like', '%' . $phone . '%')->first()) {
                $item->client_uuid = $client->uuid;
                $item->save();
            }
            else{
                $i[] = $item->uuid;
            }

        }

        dd(count($i));




        dd($lead,$phone = $lead['custom_fields_values'][11]['values'][0]['value'],$client->phone ?? null);
    }


}
