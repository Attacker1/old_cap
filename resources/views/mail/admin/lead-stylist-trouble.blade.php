<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Проект Зэ Капсула</title>
</head>

<body>
<div style="max-width:600px;min-width:500px; margin: 0 auto;background: #fff;">
    <div style="padding:30px;">
        <h2>Привет!</h2>
        <p>
            Мы видим, что
            <a href="{{ route('leads.edit',$uuid) }}" target="_blank">сделка</a>
            находится более 2-х дней в статусе "Проблема с подбором".
        </p>
        <ul>
            <li>Если ты до сих пор ждешь ответа от клиента, то помни, что если клиент не ответил в течение дня-собираем капсулу с текущими данными.</li>
            <li>Если ты ждёшь пополнения стока, то давай собирать подборку из того, что есть на стоке. Как показывает практика, если не было подходящих возвратов в течение двух дней, то идеальной вещи мы не дождемся.</li>
            <li>Если ты ждешь информации от поддержки, то запроси напрямую через Slack.</li>
        </ul>
        <p>Все еще сложно или непонятно?</p>
        <p>
            Напиши своему менеджеру, он обязательно поможет.
            <br>
            С любовью, Capsula
        </p>
    </div>
</div>

<div class="logo">
    <div style="max-width:600px;min-width:500px; margin: 0 47%;">
        <img src="{{asset('app-assets/images/capsula/logo-capsula.png')}}">
    </div>
</div>

<style>
    body {
        min-height: 600px;
        background: white;
        margin: 0;
        padding: 0;
        font-style: normal;
        font-stretch: normal;
        text-decoration: none;
        font-weight: 300;
        font-size: 15px;
    }

    a {
        text-align: left;
        color: white;
        background: #00e3a7;
        padding: 2%;
        text-decoration: none;
        font-weight: 300;
    }

    .logo {
        background: #fff;
        margin-top: 40px;
        height: 40px;
        width: 100%;
    }

</style>

</body>
</html>