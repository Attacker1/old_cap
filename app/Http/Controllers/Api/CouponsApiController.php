<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Models\Common\Coupon;


class CouponsApiController extends Controller
{ 

    public function index(Request $request)
    {

        if(!$request->action) return response('', 400);
        if(!$request->params) return response('', 400);

        $params = $request->params;

        switch($request->action) {
            case 'check':

                $validator = Validator::make($params, [
                    'name' => 'required|string| max:20'
                ]);
                if($validator->fails()) return response()->json(['result' => false, 'errors' => $validator->errors()]);
                
                $coupon = Coupon::where('name', $params['name'])->first();
                if($coupon) return response()->json(['result' => true, 'coupon'=>$coupon]);
                return response()->json(['result' => false, 'errors' =>"not found"]);
                break;

            default: return response('method not found', 400);    
        }
        

    }

}