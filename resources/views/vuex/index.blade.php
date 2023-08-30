<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>
    <link rel="stylesheet" href="{{mix('assets-vuex/css/index.css')}}"/>
    <link rel="stylesheet" href="{{mix('assets-vuex/css/backend-styles.css')}}"/>
</head>
<body>
<div id="app">
    <router-view :settings="{{json_encode($settings, JSON_UNESCAPED_UNICODE)}}"></router-view>
</div>

<script src="{{mix('assets-vuex/ts/index.js')}}"></script>

</body>
</html>
