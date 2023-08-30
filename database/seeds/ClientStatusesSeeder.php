<?php

use Illuminate\Database\Seeder;
use App\Http\Models\AdminClient\ClientStatus;

class ClientStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ClientStatus::create([
            'name' => 'без сделок',
            'slug' => 'no-deals'
        ]);

        ClientStatus::create([
            'name' => 'повторный',
            'slug' => 'repeated'
        ]);

        ClientStatus::create([
            'name' => 'новый',
            'slug' => 'new'
        ]);

    }
}
