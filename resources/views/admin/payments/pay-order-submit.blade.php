@extends('admin-clients.layouts.main-not-authorized')
@section('title'){{ $title }}@endsection
@section('content')
    <div class="main-wrapper">
        <div class="menu-top">
            <a href="https://thecapsula.ru" style="display: flex; flex-direction: row; flex-wrap: nowrap">
                <img src="{{asset('/app-assets/images/capsula/logo-capsula.png')}}" class="brand-logo-cap" alt="logo">
                <h2 class="brand-text-cap mb-0">Capsula</h2>
            </a>
        </div>
        <div class="content-wrapper">

            <div class="content-wrapper-dialog">
                <img class="content-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}" alt="dialog">
                <div class="content-text">
                    @if($error_message == '')
                        Стоимость услуг стилиста — <b>{{$data->summ}}</b> рублей, но мы вычтем их из общей суммы покупки
                    @else
                        {{ $error_message }}
                    @endif
                </div>
                <div class="content-dialog"></div>
            </div>
            <div class="break-flex"></div>
            @if(empty($error_message))
                <div class="btn-cap" style="cursor: pointer">Оплатить</div>
            @endif
        </div>
        @if(empty($error_message))
            @include('common.pay-form',
                ['pay_form'=>[
                    'terminalkey'=>config('config.TINKOFF_TERMINAL_KEY'),
                    'amount' => $data->summ,
                    'order' => 'le' . $data->uuid,
                    'description'=>'Оплата услуг стилиста',
                    'name' => $data->clients->name. ' '.$data->clients->second_name,
                    'email' =>  $data->clients->email,
                    'phone' =>  $data->clients->phone,
                    'receipt' => json_encode([
                        "Email" => $data->clients->email,
                        'Phone' => $data->clients->phone,
                        'EmailCompany' => 'ps@thecapsula.ru',
                        'Taxation' => 'usn_income_outcome',
                        'Items' => [[
                            'Name' => 'Оплата услуг стилиста',
                            'Price' => $data->summ * 100,
                            'Quantity' => 1,
                            'Amount' => $data->summ * 100,
                            'PaymentMethod' => 'full_payment',
                            'PaymentObject' => 'service',
                            'Tax' => 'none'
                        ]]
                    ], JSON_UNESCAPED_UNICODE)
                 ]
            ])
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        window.alert = function(){};
        $('.btn-cap').on('click', ()=>{$('form').submit();} );
    </script>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/admin-clients/not-authorized-pay.bundle.css')}}">
@endsection
