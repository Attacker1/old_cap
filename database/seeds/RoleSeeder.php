<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Admin\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = new Role();
        $manager->name = 'Менеджер';
        $manager->slug = 'manager';
        $manager->save();

        $developer = new Role();
        $developer->name = 'Разработчик';
        $developer->slug = 'developer';
        $developer->save();

        $stylist = new Role();
        $stylist->name = 'Стилист';
        $stylist->slug = 'stylist';
        $stylist->save();
    }
}
