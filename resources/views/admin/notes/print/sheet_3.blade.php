<div class="col-sm-12 row paper-a4">
    <div class="print-amo-id">{{ @$data->order_id }}</div>
    <div class="print-logo-capsula">
        <img src="/app-assets/images/capsula/logo-horisontal.svg" class="pring-logo-capsula" width="320" >
    </div>

<div class="col-sm-10 offset-1 price-table-div">
    <div class="col-sm-12">
    <table class="print-table table note-print-form no-border ">
        <tbody>
        <tr class="print-table-head btm-border">
            <td class="w-50" colspan="2">Вещи из подборки</td>
            <td class="w-25"></td>
            <td class="w-50 font-weight-bold text-right">Стоимость</td>
        </tr>

        @if($count)
            @php
                $i = 0;
                $len = count($price);
            @endphp
            @foreach($price as $item)
                <tr @if($i != $len - 1) class="btm-border" @endif>
                    <td colspan="3">{{ @$item->name }} {{ @$item->brands->name }}</td>
                    <td class="font-weight-bold text-right">{{ number_format(round($item->price), 0, '.', ' ')  }} руб.</td>
                </tr>
                @php $i++; @endphp
            @endforeach
        @endif
        </tbody>
    </table>
    </div>
    <div class="col-sm-8 offset-4">
        <table class="print-table table note-print-form no-border">
            <tr class="btm-border">
                <td class="text-left">Общая стоимость</td>
                <td class="font-weight-bold text-right">{{  number_format(round(@$price->sum('price')), 0, '.', ' ') }}
                    руб.
                </td>
            </tr>

            @if(!empty($paid))
            <tr class="btm-border">
                <td class="text-left">Скидка на услуги стилиста</td>
                <td class="font-weight-bold text-right">@if(!empty($paid))
                        - {{ @$paid }}@else 0 @endif руб.
                </td>
            </tr>
            @endif

            <tr class="btm-border">
                <td class="text-left">Купите все вещи со скидкой {{@$client_discounts['buy_all_products']}}%</td>
                <td class="font-weight-bold text-right">
                    - {{ number_format(round((($price->sum('price') - ($paid ?? 0)) * @$client_discounts['buy_all_products']/100)), 0, '.', ' ') }} руб.
                </td>
            </tr>

            <tr class="btm-border">
                <td class="text-left">Доставка и возврат</td>
                <td class="font-weight-bold text-right">Бесплатно</td>
            </tr>
            <tr>
                <td class="text-left">При покупке всех вещей</td>
                <td class="font-weight-bold text-right">{{ number_format(round(((($price->sum('price')) - ($paid ?? 0)) * (1-$client_discounts['buy_all_products']/100))), 0, '.', ' ')  }}
                    руб.
                </td>
            </tr>

        </table>
    </div>

    <div class="col-sm-12 mt-3">
        <ul class="note-print-form-text offset-2">
            @if(!empty($paid))
            <li>Не забывайте, что <b>{{ $paid ?? 0 }}</b> рублей уже предоплачены по вашему заказу при покупке от одной вещи
            </li>
            @endif
            @isset($client_discounts['buy_all_products'])<li>При покупке всех вещей вы получаете дополнительную скидку <b>{{ @$client_discounts['buy_all_products'] }}%</b> на все вещи</li>@endif
            @isset($client_discounts['3_4_5_goods'])<li>При покупке от 3х вещей вы получаете дополнительную скидку <b>{{ @$client_discounts['3_4_5_goods'] }}%</b> на все вещи</li>@endif
            <li>Если вы нашли вещь дешевле – напишите своему менеджеру</li>
            <li>Ваш реферальный код – <b>{{ @$promocode }}</b>. Ваш друг может указать этот номер в
                разделе промокода. Он получит скидку <b>50%</b> на услугу стилиста. А вы –
                <b>500 рублей</b>, которые можно будет потратить на одежду.
                Подробнее на <a href="https://my.thecapsula.ru/bonuses">my.thecapsula.ru/bonuses</a>
            </li>
        </ul>
    </div>
</div>

</div>
</div>
<link rel="stylesheet" type="text/css" href="/assets/css/style.css?t=<?php echo(microtime(true).rand()); ?>">