<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;


/**
 * Класс проверки соответствия пользователя и запрашиваемого действия на доступные права
 * Class Acl
 * @package App\Http\Classes
 */
class Acl
{

    /**
     * Проверка наличия прав у пользователя для запрашиваемого действия
     * @param $action
     * @param $user_id
     * @return bool
     */
    public static function permissionsTo($action, $user_id)
    {

        $res = DB::table('role_user')
            ->leftJoin('user','user.user_id','role_user.user_id')
            ->leftJoin('role','role.role_id','role_user.role_id')
            ->leftJoin('permission_role','permission_role.role_id','role_user.role_id')
            ->leftJoin('permissions','permissions.id', 'permission_role.permission_id')
            ->where('action',$action)
            ->where('role_user.user_id',$user_id)
            ->count();
        return $res ?? false;
    }






}
