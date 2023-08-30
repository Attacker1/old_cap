<!DOCTYPE html>
<html lang="">
<head>
    <title>Анкета для девушек Capsula</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no,width=device-width,initial-scale=1,maximum-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
{{--    <script src="https://points.boxberry.de/js/boxberry.js"></script>--}}
{{--    <script src="https://checkout.cloudpayments.ru/checkout.js"></script>--}}
{{--    <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>--}}

    <link id="favicon-node" rel="shortcut icon"  href="{{asset('/assets-vuex/img/favicon.ico')}}">
    @if(config('app.env') == 'local')
        <link rel="stylesheet" href="{{mix('assets-vuex/css/anketa.css')}}"/>
    @else
        <link rel="stylesheet" href="{{asset('assets-vuex/css/anketa.css')}}"/>
    @endif


    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '904387120352076');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=904387120352076&ev=PageView&noscript=1"
        /></noscript>
    @include('admin-clients.anketa.google-tag-manager-head')
</head>
<body>

@include('admin-clients.anketa.google-tag-manager-body')

<div id="app">
    <router-view :backend="{{$backend}}"></router-view>
</div>
@if(config('app.env') == 'local')
    <script src="{{mix('assets-vuex/ts/anketaFrontend.js')}}"></script>
@else
    <script src="{{asset('assets-vuex/ts/anketaFrontend.js')}}"></script>
@endif



</body>
</html>

