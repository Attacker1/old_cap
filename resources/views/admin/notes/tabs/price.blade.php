{{--  Лист СТОИМОСТЬ --}}
<div class="col-sm-12">
    <table class="table" id="pricetable">
        <thead class="thead-light">
        <tr>
            <th class="w-25">Вещи из подборки</th>
            <th class="w-25"></th>
            <th class="w-25"></th>
            <th class="w-50 font-weight-bold">Стоимость</th>
        </tr>
        </thead>

        @if($count)
            @foreach($price as $item)
                <tr>
                    <td colspan="3">{{ @$item->name }}</td>
                    <td class="font-weight-bold">{{ number_format(round($item->price), 0, '.', ' ') }} руб.</td>
                </tr>
            @endforeach
                <tr>
                    <td ></td>
                    <td  colspan="2" class="text-right">Общая стоимость</td>
                    <td class="font-weight-bold">{{ number_format(round(@$price->sum('price')), 0, '.', ' ') }} руб.</td>
                </tr>
                @if($paid > 0)
                <tr>
                    <td ></td>
                    <td  colspan="2" class="text-right">Скидка на услуги стилиста</td>
                    <td class="font-weight-bold">@if(!empty($paid))- {{ $paid }}@else 0 @endif  руб.</td>
                </tr>
                @endif
                <tr>
                    <td ></td>
                    <td  colspan="2" class="text-right">Купите все вещи со скидкой {{@$client_discounts['buy_all_products']}}%</td>
                    <td class="font-weight-bold"> - {{ number_format(round((($price->sum('price') - ($paid ?? 0)) * @$client_discounts['buy_all_products']/100)), 0, '.', ' ') }} руб.</td>
                </tr>

                <tr>
                    <td></td>
                    <td  colspan="2" class="text-right">Доставка и возврат</td>
                    <td class="font-weight-bold">Бесплатно</td>
                </tr>
                <tr>
                    <td ></td>
                    <td colspan="2" class="text-right">При покупке всех вещей</td>
                    <td class="font-weight-bold">{{ number_format(round(((($price->sum('price')) - ($paid ?? 0)) * (1-@$client_discounts['buy_all_products']/100))), 0, '.', ' ')  }} руб.</td>
                </tr>

        @endif
    </table>
    <ul class="price-info offset-2">
        @if($paid > 0)
        <li>Не забывайте, что <b>{{ $paid ?? 0 }}</b> рублей уже предоплачены по вашему заказу при покупке от одной вещи
        </li>
        @endif
        <li><p>При покупке всех вещей вы получаете дополнительную скидку <b>{{@$client_discounts['buy_all_products']}}%</b> на все вещи</p></li>
        <li><p>При покупке от 3х вещей вы получаете дополнительную скидку <b>{{@$client_discounts['3_4_5_goods']}}%</b> на все вещи</p></li>
        <li>Если вы нашли вещь дешевле – напишите своему менеджеру</li>
            <li>Ваш реферальный код – <b>{{ @$promocode }}</b>.  Ваш друг может указать этот номер в разделе промокода. Он получит скидку <b>50%</b> на услугу стилиста. А вы – <b>500 рублей</b>,
                которые можно будет потратить на одежду.<br>Подробнее на <a href="https://my.thecapsula.ru/bonuses">my.thecapsula.ru/bonuses</a>
        </li>

    </ul>
</div>
{{-- END Лист СТОИМОСТЬ  --}}

