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
        <p>Вы назначены на Новую сделку с клиентом.
            <br><small>ID сделки {{ @$uuid }}</small>
        </p>
        <p>
            <a href="{{ route('leads.edit',$uuid) }}" target="_blank">Перейти к сделке</a>
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