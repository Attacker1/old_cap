<?php

namespace App\Http\Controllers\Catalog;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Catalog\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Mockery\Exception;

class FeedbackController extends Controller
{
    /**
     * Список анкет ОС
     *
     * @return View
     */

    public function list()
    {
        $feedbackData = FeedbackgeneralQuize::has('feedbackgQuize')->get();
        return view('admin.feedback.list', [
            'title' => 'Обратная связь',
            'feedbackData' => $feedbackData
        ]);
    }

    /**
     * Анкета ОС
     *
     * @param FeedbackgeneralQuize $item
     * @return View
     */

    public function show(FeedbackgeneralQuize $item)
    {

        $feedbackGeneralData = $item;
        $feedbackData = $feedbackGeneralData->toArray();

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
            $product = Product::where('id', $arr_new['product_id'])->first();
            $arr_new['img_url'] = $product->attachments()->where( 'main', 1)->first()->url;
            $feedbackData['products'][] = $arr_new;
        }

        return view('admin.feedback.show', [
            'title' => 'Анкета Обратная связь',
            'feedbackData' => $feedbackData
        ]);
    }

    /**
     * Удаление Анкеты ОС
     *
     * @param FeedbackgeneralQuize $item
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function delete(FeedbackgeneralQuize $item)
    {
        if(!auth()->guard('admin')->user()->can('destroy-feedback-quizes'))
        {
            return redirect()->route('feedback.list');
        }

        $feedbgeneralModel = $item;
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