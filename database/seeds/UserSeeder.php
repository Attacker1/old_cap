<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = Role::where('slug','developer')->first();
        $manager = Role::where('slug', 'manager')->first();
        $stylist = Role::where('slug', 'stylist')->first();

        $createProducts = Permission::where('slug','create-products')->first();
        $manageUsers = Permission::where('slug','manage-users')->first();

        $user1 = new User();
        $user1->name = 'Разработчик Тест Пользователь';
        $user1->email = 'developer@mail.com';
        $user1->password = bcrypt('secret');
        $user1->save();
        $user1->roles()->attach($developer);
        $user1->permissions()->attach($manageUsers);
        $user1->permissions()->attach($createProducts);

        $user2 = new User();
        $user2->name = 'Менеджер Тест Пользователь';
        $user2->email = 'manager@mail.com';
        $user2->password = bcrypt('secret');
        $user2->save();
        $user2->roles()->attach($manager);
        $user2->permissions()->attach($createProducts);

        $user2 = new User();
        $user2->name = 'Стилист Тест Пользователь';
        $user2->email = 'stylist@mail.com';
        $user2->password = bcrypt('secret');
        $user2->save();
        $user2->roles()->attach($stylist);
        $user2->permissions()->attach($createProducts);
    }
}
