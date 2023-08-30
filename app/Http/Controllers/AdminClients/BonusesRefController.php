<?php


namespace App\Http\Controllers\AdminClients;
use App\Http\Models\AdminClient\Client;


class BonusesRefController
{
    public function index()
    {
        $bonuses = auth()->guard('admin-clients')->user()->bonuses;

        $points =  $bonuses->points ?? "0";
        $points = (string)$points;

        $last_number = $points[strlen($points)-1];
        $bonus_text = '';
        if(in_array($last_number, [0, 5, 6, 7, 8, 9])) $bonus_text = 'рублей'; 
        if(in_array($last_number, [1])) $bonus_text = 'рубль';
        if(in_array($last_number, [2, 3, 4])) $bonus_text = 'рубля';

        return view('admin-clients.bonuses', [
            'title' => 'Бонусы',
            'bonuses' => $bonuses,
            'bonus_text' => $bonus_text
        ]);
    }
}