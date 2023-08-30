<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Capsula: @yield('title')</title>
    <link rel="apple-touch-icon" href="/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/extensions/tether-theme-arrows.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/extensions/tether.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/extensions/shepherd-theme-default.css">

    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <!--<link rel="stylesheet" type="text/css" href="/app-assets/css/bootstrap.css">-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/dashboard-analytics.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/card-analytics.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/tour/tour.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <!--<link href="/css/app.css" rel="stylesheet">-->
    <link rel="stylesheet" href="{{asset('app-assets/css/capsula-admin-clients.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/179b10d737.js" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?t=<?php echo(microtime(true).rand()); ?>">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/app-assets/css/capsula-admin-clients.css')}}">
    <!-- END: Custom CSS-->
    <style>
        .header-navbar {font-family: Poppins,Helvetica,sans-serif !important;}
        .header-navbar.navbar {
            padding-top: 0px;
            padding-bottom: 0px;
        }
    @media screen and (max-width: 575.98px) {
        .header-navbar.floating-nav {
            width: 100% !important;
            margin-right: 0rem !important;
            margin-top: 0rem;
        }
    }
    </style>
    @yield('css')
</head>

<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  @yield('navbar', '')" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

@include('admin-clients.layouts.ya-metrica')

<!-- BEGIN: Header-->
@include('admin-clients.layouts.topnav')
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
@include('admin-clients.layouts.leftmenu')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="content-header-left col-md-6 col-6 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@yield('title')</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                @yield('actions')
            </div>

        </div>
        <div class="content-body">
            <!-- Content -->
            <section>
                @yield('content')
            </section>

            <!-- Content end -->

        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('admin-clients.layouts.footer')

<script>

    let env = '{{ config('app.env') ?? 'local' }}';
    $('.btn-repeat-order').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_3')
        } else console.log('3');
    });

    $('.watsapp-link').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_4')
        } else console.log('4');
    });

    $('.manager-chat-button').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_5')
        } else console.log('5');
    });

    $('.orders-button').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_6')
        } else console.log('6');
    });

    $('.mark-and-pay-btn-in-orders').on('click', ()=>{

        if(env == 'production') {
            ym(82667803,'reachGoal','goal_7')
        } else console.log('7');
    });

    $('.pay-btn-in-orders').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_8')
        } else console.log('8');
    });

    $('.bonuses-btn').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_9')
        } else console.log('9');
    });

    $('.btn-share-link').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_10')
        } else console.log('10');
    });

    $('.btn-copy-code').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_11')
        } else console.log('11');
    });

    $('.btn-anketa').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_12')
        } else console.log('12');
    });

    $('.btn-exit-lk').on('click', ()=>{
        if(env == 'production') {
            ym(82667803,'reachGoal','goal_14')
        } else console.log('14');
    });


</script>


@yield('scripts')

    </body>
</html>

    </body>
</html>
