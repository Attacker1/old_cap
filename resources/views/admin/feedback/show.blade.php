@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        @if(!empty($feedbackData['lead_id']))
            <a href="{{ route('leads.edit',$feedbackData['lead_id']) }}" target="_blank"><i
                        class="fas fa-external-link-alt text-primary mr-3"></i></a>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        {{--Статистика--}}
        <div class="card card-custom col-lg-12 mb-6" style="margin-right: 20px;">
            <div class="card-header">
                <div class="card-title">
                    Статистика
                </div>
            </div>
            <div class="card-body">
                <div class="row" style="border-bottom: 1px solid #ebedf3">
                    <table
                            class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline "
                            style="width: 100%;border-color: #dedede">
                        <tbody>
                        @if(!empty($feedbackData['products']))
                            <?php
                            $buyout = 0;
                            $quality = 0;
                            $length = 0;
                            $price = 0;
                            foreach ($feedbackData['products'] as $data) {
                                if ($data['action_result'] == 'Купил(а)') {
                                    $buyout++;
                                }
                                if ($data['quality_opinion'] !== null) {
                                    $quality += (int)$data['quality_opinion'] * 100 / 5;
                                }
                                if ($data['product_length'] !== null) {
                                    $length += (int)$data['product_length'] * 100 / 5;
                                }
                                if ($data['price_opinion'] !== null) {
                                    $price += (int)$data['price_opinion'] * 100 / 5;
                                }
                            }
                            ?>
                            <tr role="row">
                                <th class="text-center">
                                    Выкуп
                                </th>
                                <th class="text-center">
                                    {{ round(100 * $buyout / (int) count($feedbackData['products']),0) . '%' }}
                                </th>
                                <th class="text-center">
                                    Кол-во выкупленных вещей
                                </th>
                            </tr>
                            <tr role="row">
                                <th class="text-center">
                                    Качество
                                </th>
                                <th class="text-center">
                                    {{ round($quality / (int) count($feedbackData['products']),0) . '%' }}
                                </th>
                                <th class="text-center">
                                    Кол-во баллов в категории «Качество» по всем вещам
                                </th>
                            </tr>
                            <tr role="row">
                                <th class="text-center">
                                    Длина
                                </th>
                                <th class="text-center">
                                    {{ round($length / (int) count($feedbackData['products']),0) . '%' }}
                                </th>
                                <th class="text-center">
                                    Кол-во баллов в категории «Длина» по всем вещам
                                </th>
                            </tr>
                            <tr role="row">
                                <th class="text-center">
                                    Цена
                                </th>
                                <th class="text-center">
                                    {{ round($price / (int) count($feedbackData['products']),0) . '%' }}
                                </th>
                                <th class="text-center">
                                    Кол-во баллов в категории «Цена» по всем вещам
                                </th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{--Вещи--}}
        @if(!empty($feedbackData['products']))
            <div class="card card-custom col-lg-12 mb-6" style="margin-right: 20px;">
                <div class="card-header">
                    <div class="card-title">
                        Вещи
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" style="border-bottom: 1px solid #ebedf3">
                        <table
                                class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline "
                                style="width: 100%;border-color: #dedede">
                            <thead style="background-color: #a6a6a6">
                            <tr>
                                <th class="text-center text-white" style="vertical-align: middle;">Категория вещи</th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Картинка
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Стоимость
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Что вы сделали с этой вещью?
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Подошел ли вам размер?
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Качество
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Цена
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Длина
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Комментарии клиента
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Ссылка на вещь в БД
                                </th>
                                <th class="text-center text-white" style="vertical-align: middle;">
                                    Размер вещи
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($feedbackData['products'] as $data)
                                <tr role="row">
                                    <th class="text-center" style="vertical-align: middle;">
                                        @if($data['product'])
                                            {{ $data['product']->name }}
                                        @endif
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        <img src="{{ $data['img_url'] }}" style="width: 100px">
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        @if($data['product'])
                                            {{ $data['product']->price }}
                                        @endif
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $data['action_result'] }}
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $data['size_result'] }}
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $data['quality_opinion'] }}
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $data['price_opinion'] }}
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $data['product_length'] }}
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $data['comments'] }}
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        @if($data['product'])
                                            <a href="{{ route('admin.catalog.products.show',$data['product']->id) }}">Ссылка</a>
                                        @endif
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        @if($data['product'])
                                            {{ $data['product']->size }}
                                        @endif
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{--Общие вопросы--}}
        <div class="card card-custom col-lg-12 mb-6" style="margin-right: 20px;">
            <div class="card-header">
                <div class="card-title">
                    Общие вопросы
                </div>
            </div>
            <div class="card-body">
                <div class="row" style="border-bottom: 1px solid #ebedf3">
                    <table
                            class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline "
                            style="width: 100%;border-color: #dedede">
                        <tbody>
                        @if($feedbackData['general_impression'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    В целом как бы вы оценили работу Capsula?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($feedbackData['general_impression'] * 100 / 5, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['personal_attitude'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Ощутили ли вы персональный подход?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($feedbackData['personal_attitude'] * 100 / 5, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['design'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Понравился ли вам стиль подборки?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($feedbackData['design'] * 100 / 5, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['buy_more'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Хотели бы вы воспользоваться услугами Capsula еще раз?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($feedbackData['buy_more'] * 100 / 5, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        <?php
                        $clothing_external_look = null;
                        if (isset($feedbackData['data']['clothing_external_look'])) {
                            if ($feedbackData['data']['clothing_external_look'] != "null")
                                $clothing_external_look = $feedbackData['data']['clothing_external_look'];
                        } ?>
                        @if($clothing_external_look !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Оцените внешний вид одежды?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($clothing_external_look * 100 / 5, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['stylist_note_wanted'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Какую информацию вы бы хотели видеть в персональной записке от стилиста?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['stylist_note_wanted'] }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['stylist_note_wanted'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Любые комментарии нам и вашему стилисту
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['other_any_comments'] }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['new_stylist'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Хотели бы вы попробовать нового стилиста в следующей капсуле?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['new_stylist'] }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['recommended'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    На сколько вероятно, что вы порекомендуете нас своим друзьям и знакомым?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($feedbackData['recommended'] * 100 / 10, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['mark_reason'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    (Опционально) Какова причина, по которой вы поставили такую оценку?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['mark_reason'] }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['mark_up_actions'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    (Опционально) Что мы можем доработать, чтобы ваша оценка улучшилась?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['mark_up_actions'] }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['repeat_date'] !== null || $feedbackData['repeat_date_own']!==null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    Когда вы хотите получить свою следующую подборку?
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['repeat_date'] }}
                                    {{ $feedbackData['repeat_date_own'] ?? ' ' . $feedbackData['repeat_date_own'] }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['delivery_mark'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    (Опционально) Ваша оценка по работе службы доставки
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ round($feedbackData['delivery_mark'] * 100 / 10, 0) . '%' }}
                                </th>
                            </tr>
                        @endif
                        @if($feedbackData['delivery_comment'] !== null)
                            <tr role="row">
                                <th class="text-center" style="vertical-align: middle;">
                                    (Опционально) Ваш комментарий по работе службы доставки
                                </th>
                                <th class="text-center" style="vertical-align: middle;">
                                    {{ $feedbackData['delivery_comment'] }}
                                </th>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
