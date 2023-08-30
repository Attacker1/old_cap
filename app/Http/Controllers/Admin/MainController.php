<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Message;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\AdminClient\Questionnaire;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Validator;
use Auth;

/**
 * Class MainController - роутинг
 * @package App\Http\Controllers\Admin
 */
class MainController extends Controller
{


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function index()
    {
        return view('admin.catalog.index', [
            'title' => 'Главная страница',
            'data' => []
        ]);
    }

}
