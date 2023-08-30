@extends('vuex.layouts.template')

@section('title') Каталог товаров @endsection

@section('css')
    @component('vuex.layouts.css',[
        'css' => [
            'assets-vuex/css/index.css',
            'assets-vuex/css/backend-styles.css'],
        'custom' => [
            '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />'
            ]
    ])
    @endcomponent
@endsection

@section('app')
    <router-view :settings="{{json_encode($settings, JSON_UNESCAPED_UNICODE)}}"></router-view>
@endsection

@section('js')
    @component('vuex.layouts.js',[
        'js' => ['assets-vuex/ts/stock.js']
        ])
    @endcomponent
@endsection
