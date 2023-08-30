<?php

namespace App\Http\Controllers\AdminClients\Transitions\Clients;

use App\Http\Classes\Common;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\Common\Bonus;
use Illuminate\Support\Str;

class TransitionDataController extends Controller
{

    public function main()
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $this->add_random();
        //dd(mt_rand(100000, 999999));

        //dump($this->searchPhoneDuplicates());
        //$this->doublePhones();
        //$this->save();
        //dump($this->searchEmailDuplicates());

        return view('admin-clients.clients-transition', [
            'title' => 'Временная страница'
        ]);
    }

    public function add_random()
    {
        $arr_all = DB::table('clients')->get()->all();
        foreach ($arr_all as $item)
        {

            $res = [];
            do {
                $code = mt_rand(100000, 999999);
                $res = DB::table('clients')->select('referal_code')->where('referal_code', $code)->first();

            } while($res!==null);

            if(isset($code)) try {
                DB::table('clients')
                    ->where('uuid', $item->uuid)
                    ->update(['referal_code' => $code]);
            } catch(QueryException $ex) {
                var_dump($res);
                dd($ex);
            }
        }
    }

    /**
     * Ищет одинаковые по всем столбцам записи
     *
     * @return \Illuminate\Support\Collection
     */

    public function searchEmailDuplicates()
    {
        return  DB::table('temp_source_clients')
            ->select('email', DB::raw('COUNT(*) AS `count`'))
            ->groupBy(['email'])
            ->havingRaw('count > 1', [])
            ->get();
    }

    /**
     * Ищет одинаковые по всем столбцам записи
     *
     * @return \Illuminate\Support\Collection
     */

    public function searchPhoneDuplicates()
    {
        return  DB::table('temp_source_clients')
            ->select('phone', DB::raw('COUNT(*) AS `count`'))
            ->groupBy(['phone'])
            ->havingRaw('count > 1', [])
            ->get();
    }

    /**
     * Сохраняет записи в постоянную таблицу Client
     *
     * @return \Illuminate\Support\Collection
     */

    public function save()
    {
        $all_clients = DB::table('temp_source_clients')->get();

        foreach ($all_clients as $item)
        {
            $Client = new Client();
            $Client->fill([
                'name' => $item->name,
                'phone' => $item->phone,
                'email' => $item->email,
                'comments' => $item->comments
            ]);

            $res = $Client->save();

        }
    }

    public function doublePhones()
    {
        foreach ($this->searchPhoneDuplicates() as $item)
        {
            $doubles =  DB::table('temp_source_clients')->where('phone', $item->phone)->get();
            $i = $item->count - 1;
            $comments = '';
            foreach ($doubles as $double) {

                if ($i != 0) {
                    $comments .= ' был дубль по номеру телефона: имя ' . $double->name . ' email ' . $double->email;
                    //удалить запись
                    DB::table('temp_source_clients')->where('id', $double->id)->delete();

                } else {
                    DB::table('temp_source_clients')
                        ->where('id', $double->id)
                        ->update([
                            'comments' => $double->comments .' ' . $comments]);
                }

                $i--;
            }
        }
    }

    public function setPromocode() 
    {
        $clients = Client::all();
        foreach ($clients as $client) {
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

        }

    }

}
