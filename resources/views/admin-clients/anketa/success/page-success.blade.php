<!DOCTYPE html>
<html>
<head>
    <title>Анкета для девушек Capsula</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no,width=device-width,initial-scale=1,maximum-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link id="favicon-node" rel="shortcut icon"  href="{{ asset('assets-vuex/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets-vuex/css/anketa.css') }} " />
</head>
<body>

@if(config('app.env') =='production')
    @include('admin-clients.anketa.success.metrica')
    @include ('admin-clients.anketa.success.facebook');
@endif


<div id='app'>
    <div class="wrap">
        <div class='main'>

            <div class='question'>
                <div class='question-head'>
                    <div class="logo">
                        <img src="{{ asset('assets-vuex/img/logo.svg') }}" alt="logo">
                    </div>
                    <label>
                    @if($OrderPrefix != 'fb')
                        Мы получили вашу оплату! В ближайшее время вам придут смс и письмо на почту от Capsula с доступом в ваш личный кабинет. В нем вы можете просмотреть заполненную анкету и связаться с вашим менеджером, если хотите внести дополнения к анкете 🙂
                    @else
                        Cпасибо! Мы получили оплату за вещи. Чек придет вам на почту. 🙂<br>
                        Если у вас есть предложения по нашей работе или качеству сервиса, вы можете написать вашему менеджеру в
                            <a href="https://api.whatsapp.com/send/?phone=78007000762&amp;text&amp;app_absent=0" target="_blank">
                                Whatsapp</a><br>
                        Если удобнее, вы можете написать на почту генеральному директору is@thecapsula.ru. Мы будем рады услышать каждое мнение и предложение.<br>
                    @endif
                    </label>
                </div>
                <div class='answers'>
                </div>
            </div>

        </div>
    </div>
</div>


@if(config('app.env') =='production' && $Success == "true")
    <script>
        @switch($OrderPrefix)
            @case ('fa')
                <?php
                    $json_fb  = '{ currency: "RUB", value: ' . $Amount . '}, { eventID: "'. $OrderId . '" }';
                    $fb_action = 'Subscribe';
                    ?>
                fbq('track', '{{ $fb_action }}' , {!! $json_fb !!});
                ym(74453200, 'reachGoal', 'Purchase');
                {{ Log::channel('fb_events')->info([ date('d.m.Y H:i:s') . $fb_action . ' ' . $json_fb ]) }}
                {{ Log::channel('ym_events')->info([ date('d.m.Y H:i:s') . ' reachGoal Purchase ' . 'OrderId: ' . $OrderId ]) }}
            @break

            @case ('re')

            @break

            @case ('cp')
                ym(74453200, 'reachGoal', 'Purchase_old');
                 {{ Log::channel('ym_events')->info([ date('d.m.Y H:i:s') . 'reachGoal Purchase_old' . 'OrderId:' . $OrderId ]) }}
            @break
        @endswitch
    </script>
@endif
<script>

    if(localStorage.getItem('redtracker') !== null) {

        var request = new XMLHttpRequest();
        request.open("POST", "/success");
        request.setRequestHeader('Content-type', 'application/json');

        var params = {
            clickid: localStorage.getItem('redtracker') ?? '',
            sum: {{$Amount}}
        }

        request.send(JSON.stringify(params));
    }
</script>


</body>
</html>
