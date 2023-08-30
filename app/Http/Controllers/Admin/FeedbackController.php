<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Catalog\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Mockery\Exception;
use Illuminate\Http\Request;
use App\Http\Models\Common\Lead;

class FeedbackController extends Controller
{
    /**
     * Список анкет ОС
     *
     * @return View
     */

    public function list(Request $request)
    {
        
        if(!auth()->guard('admin')->user()->can('manage-feedback') && !auth()->guard('admin')->user()->can('viewing-feedback-list-own'))
        {
            return redirect()->route('admin.main.index');
        }
        
        if(request()->ajax())
        {
            
            $arr_columns = [
                1=>'id',
                2=>'amo_id',
                3=>'clients.name',
                4=>'lead_id',
                5=>'status',
                6=>'feedbackgeneral_quizes.created_at'];

            $request_params = $request->all();
            
            $order_column = $arr_columns[$request_params['order'][0]['column']];

            $order_dir = $request_params['order'][0]['dir'];

            $pagination_start = $request_params['start'];
            
            $pagination_length = $request_params['length'];

            $search_value = $request_params['search']['value'];

            session()->forget('feedback_data');
            session()->push('feedback_data', [
                'order_column' => $request_params['order'][0]['column'],
                'order_dir' => $order_dir,
                'search_value' => $search_value,
                'limit_menu' => $pagination_length,
                'paging_start' => $pagination_start
            ]);


            $feedback = new FeedbackgeneralQuize();
            //$feedback = FeedbackgeneralQuize::leftJoin('clients','feedbackgeneral_quizes.client_uuid','=','clients.uuid')->select($arr_columns);

            if(auth()->guard('admin')->user()->can('viewing-feedback-list-own')) {
                $stylist_id = auth()->guard('admin')->user()->id;
                $arr_feedback_ids = Lead::where('stylist_id', $stylist_id)->get()->pluck('uuid')->toArray();
                $feedback = $feedback->whereIn('lead_id', $arr_feedback_ids);
            }

            if($search_value != '')
            {
                $feedback = $feedback
                    ->where('id',  'LIKE' , "%{$search_value}%")
                    ->orWhere('client_uuid', 'LIKE' , "%{$search_value}%")
                    ->orWhere('amo_id', 'LIKE' , "%{$search_value}%")
                    ->orWhere('amount', 'LIKE' , "%{$search_value}%");

                $recordsFiltered = $feedback->count();
            }
            echo print_r($request_params); die();
            $feedback = $feedback
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)
                ->get()
                ->toArray();

            /*$feedback
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)->dump();*/

            if(auth()->guard('admin')->user()->can('viewing-feedback-list-own')) {

                $recordsTotal = FeedbackgeneralQuize:: whereIn('id', $arr_feedback_ids)->count();

            } else $recordsTotal = FeedbackgeneralQuize::count();

            $recordsFiltered = $recordsFiltered ?? $recordsTotal;    

            $res = [
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=>$feedback,
                'temp'=>session()->get('feedback_data')
            ];

            return json_encode($res);
        }

        if(session('status_success')) {
            toastr()->success(session('status_success'));
        }

        if(session('status_error')) {
            toastr()->error(session('status_error'));
        }

        $datatable_params[0]= [
            'order_column' => '1',
            'order_dir' => 'asc',
            'search_value' => '',
            'limit_menu' => '10',
            'paging_start' => 0
        ];

        if(session()->has('feedback_data'))
        {
            $datatable_params = session()->get('feedback_data', 'default');
        }

        return view('admin.feedback.list',[
            'title' => 'Управление фидбэк',
            'datatable_params' => $datatable_params[0]
        ]);
    }

    public function reset_datatable_settings()
    {
        session()->forget('feedback_data');
        return redirect()->route('admin.feedback.list.fill');
    }

    /**
     * Анкета ОС
     *
     * @param  int  $id
     * @return View
     */

    public function show($id)
    {

        
        $feedbackGeneralData = FeedbackgeneralQuize::find($id);
        $feedbackData = $feedbackGeneralData->toArray();

//        $length = FeedbackQuize::where('feedbackgeneral_quize_id', $feedbackData['id'])->get();


        switch($feedbackData['new_stylist'])
        {
            case 'yes' : $feedbackData['new_stylist'] = 'Да';
            break;

            case 'no' : $feedbackData['new_stylist'] = 'Нет';
            break;
        }

        switch($feedbackData['repeat_date'])
        {
            case 'week' : $feedbackData['repeat_date'] = 'Через неделю (скидка -700 руб. на услуги стилиста)';
            break;

            case 'month' : $feedbackData['repeat_date'] = 'Через месяц (скидка -700 руб. на услуги стилиста)';
            break;

            case 'two_months' : $feedbackData['repeat_date'] = 'Через 2 месяца (скидка -500 руб. на услуги стилиста)';
            break;

            case 'half_year' : $feedbackData['repeat_date'] = 'Через полгода';
            break;

            case 'other' : $feedbackData['repeat_date'] = 'Другое';
            break;
        }

        $feedbackgQuizeModels = $feedbackGeneralData->feedbackgQuize;

        foreach($feedbackgQuizeModels as $data)
        {
            $arr_new = $data->toArray();

            switch ($arr_new['action_result'])
            {
                case 'buy': $arr_new['action_result'] = 'Купил(а)';
                break;

                case 'return': $arr_new['action_result'] = 'Вернула(а)';
                break;
            }

            switch ($arr_new['size_result'])
            {
                case 'small': $arr_new['size_result'] = 'Мал';
                break;

                case 'ok': $arr_new['size_result'] = 'Как раз';
                break;

                case 'big': $arr_new['size_result'] = 'Большой';
                break;
            }
            $product = Product::with('cats')->where('id', $arr_new['product_id'])->first();
            
            $arr_new['img_url'] = '';
            $arr_new['product'] = null;
            if($product) {
                $arr_new['img_url'] = $product->attachments()->where( 'main', 1)->first()->url;
                $arr_new['product'] = $product;
            } else  if($data['old_url']) $arr_new['img_url'] = $data['old_url'];

            $arr_new['product_length'] = $data['data']['product_length'] ?? null;

            $feedbackData['products'][] = $arr_new;
        }

        return view('admin.feedback.show', [
            'title' => 'Обратная связь. Фидбек №' . $id,
            'feedbackData' => $feedbackData
        ]);
    }

    /**
     * Удаление Анкеты ОС
     *
     * @param  int  $id
     * @return RedirectResponse|Redirector
     */
    public function delete($id)
    {
        if(!auth()->guard('admin')->user()->can('destroy-feedback-quizes'))
        {
            return redirect()->route('feedback.list');
        }

        $feedbgeneralModel = FeedbackgeneralQuize::find($id);
        if(is_null($feedbgeneralModel))
        {
            return redirect()->route('feedback.list');
        }

        try {
            $feedbgeneralModel->feedbackgQuize;
        }
        catch (\Exception $e)
        {
            toastr()->error('Ошибка удаления');
            return redirect()->route('feedback.list');
        }

        $feedbModel = $feedbgeneralModel->feedbackgQuize()->delete();
        $feedbgeneralModel->delete();
        toastr()->success('Запись успешно удалена');
        return redirect()->route('feedback.list');
    }
}