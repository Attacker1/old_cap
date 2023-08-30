<?php

namespace App\Http\Controllers\AdminClients\Transitions\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Classes\Common;


class FormatDataController extends Controller
{

    public function main()
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        dump($this->searchComments());
        $this->clearPhones();
        $this->formatPhones();

        return view('admin-clients.clients-transition', [
            'title'=>'Временная страница'
        ]);
    }

    /**
     * Ищет комментарии в столбце с номерами телефонов
     *
     * @return array
     */

    public function searchComments()
    {
        $arr_all = DB::table('temp_source_clients')->get()->all();
        $arr_phones = array_column($arr_all, 'phone');
        $arr_phones_comments = [];

        foreach ($arr_phones as $phone)
        {
            $re = '/\D/mu';
            $str = $phone;

            //ищем где есть не цифры
            preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
            if(!count($matches))  continue;

            $matches = array_map(function($arr){
                return $arr[0];},$matches);

            //ищем первый номер телефона если их несколько
            $re = '/[0-9]+/mu';
            $str = $phone;

            preg_match($re, $str, $matches1, PREG_OFFSET_CAPTURE, 0);

// Print the entire match result

            $arr = [
                'phone' => $phone,
                'comment' => implode('',$matches),
                'phone_new' => $matches1[0][0] ?? ''
            ];

            $arr_phones_comments[] = $arr;
        }
        return $arr_phones_comments;
    }

    /**
     * Убирает строки из телефона и добавляет их в коммент
     *
     *
     * @return void
     */

    public function clearPhones()
    {
        $phones_to_format = $this->searchComments();
        foreach ($phones_to_format as $item)
        {
            DB::table('temp_source_clients')
                ->where('phone', $item['phone'])
                ->update(['phone' => $item['phone_new'], 'comments' => $item['comment']]);
        }
    }

    public function formatPhones()
    {
        $arr_all = DB::table('temp_source_clients')->get()->all();
        foreach ($arr_all as $item)
        {
            $item->new_phone = Common::format_phone($item->phone);

            DB::table('temp_source_clients')
                ->where('id', $item->id)
                ->update(['phone' => $item->new_phone]);
        }
    }

}