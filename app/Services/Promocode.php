<?php

namespace App\Services;

use App\Http\Models\AdminClient\Client;
use App\Http\Models\Common\Bonus;
use Illuminate\Support\Str;

class Promocode {

    public function set(Client $client) {

        $name = strstr($client->name, ' ',true);
        if(empty($name)) $name = $client->name;

        $promocode_name = Str::slug($name);
        $promocode_phone = substr($client->phone, -4);
        $promocode = substr($promocode_name . $promocode_phone,0, 18);

        $i = 0;

        do {
            $check_promo = Bonus::where('promocode', $promocode)->first();

            if ($check_promo) {
                $promocode .= (string)$i;
                $i++;
            }

        } while ($check_promo && $i < 100);

        $bonuses = new Bonus();
        $bonuses->client_id = $client->uuid;
        $bonuses->promocode = $promocode;
        $bonuses->save();

        return $promocode;

    }

}