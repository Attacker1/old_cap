<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Common\LeadRef;

class LeadRefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeadRef::create([
            'id' => 0,
            'name' => 'Ручное создание'
        ]);

        LeadRef::create([
            'id' => 1,
            'name' => 'Заполненная анкета'
        ]);

        LeadRef::create([
            'id' => 2,
            'name' => 'Анкета оплачена'
        ]);

        LeadRef::create([
            'id' => 3,
            'name' => 'Готова к назначению'
        ]);

        LeadRef::create([
            'id' => 4,
            'name' => 'Анкета у стилиста'
        ]);

        LeadRef::create([
            'id' => 5,
            'name' => 'Проблема с подбором'
        ]);

        LeadRef::create([
            'id' => 6,
            'name' => 'Подборка составлена'
        ]);

        LeadRef::create([
            'id' => 7,
            'name' => 'Собирается'
        ]);

        LeadRef::create([
            'id' => 8,
            'name' => 'Отгружена'
        ]);

        LeadRef::create([
            'id' => 9,
            'name' => 'Одежда у клиента'
        ]);

        LeadRef::create([
            'id' => 10,
            'name' => 'ОС отправлена'
        ]);

        LeadRef::create([
            'id' => 11,
            'name' => 'В логистике'
        ]);

        LeadRef::create([
            'id' => 12,
            'name' => 'Требуется оплата'
        ]);

        LeadRef::create([
            'id' => 13,
            'name' => 'Заказ оплачен'
        ]);

        LeadRef::create([
            'id' => 14,
            'name' => 'Сделка закрыта'
        ]);

    }
}
