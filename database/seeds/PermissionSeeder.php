<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Admin\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manageUser = new Permission();
        $manageUser->name = 'Управление пользователям';
        $manageUser->slug = 'manage-users';
        $manageUser->save();
        $createTasks = new Permission();
        $createTasks->name = 'Создание товаров';
        $createTasks->slug = 'create-products';
        $createTasks->save();
    }
}
