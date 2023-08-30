<form name="TinkoffPayForm" ref="TinkoffPayForm" onsubmit="pay(this); return false;">
    <input class="tinkoffPayRow" type="hidden" name="terminalkey" value="{{ $pay_form['terminalkey'] }}"> <!-- ключ
тестовых платежй -->
    <input class="tinkoffPayRow" type="hidden" name="frame" value="false">
    <input class="tinkoffPayRow" type="hidden" name="language" value="ru">
    <input class="tinkoffPayRow" type="hidden" placeholder="Сумма заказа" name="amount" required value="{{ $pay_form['amount'] }}">
    <!-- считается по заполненной формe -->
    <input class="tinkoffPayRow" type="hidden" placeholder="Номер заказа" name="order" value="{{ $pay_form['order'] }}">
    <!-- формат:fb000000, где "fb"-фиксированный префикс, а 000000 ID фидбека в нашей БД -->
    <input class="tinkoffPayRow" type="hidden" placeholder="Описание заказа" name="description"
           value="{{ $pay_form['description'] }}">
    <input class="tinkoffPayRow" type="hidden" placeholder="ФИО плательщика" name="name" value="{{ $pay_form['name'] }}">
    <!-- из профиля-->
    <input class="tinkoffPayRow" type="hidden" placeholder="E-mail" name="email" value="{{ $pay_form['email'] }}"> <!-- из
профиля-->
    <input class="tinkoffPayRow" type="hidden" placeholder="Контактный телефон" name="phone"
           value="{{$pay_form['phone']}}"> <!-- из профиля-->

    <input class="tinkoffPayRow" type="hidden" name="receipt" value = '<?php echo $pay_form['receipt'] ?>' >

    <input class="tinkoffPayRow" type="submit" value="Оплатить">
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>