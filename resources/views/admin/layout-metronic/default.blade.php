<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ App\Classes\Theme\Metronic::printAttrs('html') }} {{ App\Classes\Theme\Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title>{{ config('app.name') }} | @yield('title', $page_title ?? '')</title>

        {{-- Meta Data --}}
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Favicon --}}
        <link rel="shortcut icon" type="image/x-icon" href="/app-assets/images/ico/favicon.ico">

        {{-- Fonts --}}
        {{ App\Classes\Theme\Metronic::getGoogleFontsInclude() }}
        
        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout-m.resources.css') as $style)
            <link href="{{ config('layout-m.self.rtl') ? asset(app\Classes\Theme\Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Layout Themes (used by all pages) --}}
        @foreach (App\Classes\Theme\Metronic::initThemes() as $theme)
            <link href="{{ config('layout-m.self.rtl') ? asset(App\Classes\Theme\Metronic::rtlCssPath($theme)) : asset($theme) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Includable CSS --}}
        @yield('styles')
    </head>

    <body {{ App\Classes\Theme\Metronic::printAttrs('body') }} {{ App\Classes\Theme\Metronic::printClasses('body') }}>

        @if (config('layout-m.page-loader.type') != '')
            @include('admin.layout-metronic.partials._page-loader')
        @endif

        @include('admin.layout-metronic.base._layout')

        <script>var HOST_URL = " route('quick-search') ";</script>

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout-m.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout-m.resources.js') as $script)
            <script src="{{ asset($script) }}"></script>
        @endforeach

        @if (config("app.env") == "production")
            @include("partials.metrika.office_metric")
        @endif

        {{-- Includable JS --}}
        @yield('scripts')
        <script src="{{ asset('m/js/sweet-alerts.js') }}"></script>

        @toastr_css
        @toastr_js
        @toastr_render
        <script>
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                toastr.options = {
                    "closeButton": true,
                    "newestOnTop": true,
                    "positionClass": "toast-bottom-right"
                };
        </script>


        <div class="body-block-loaders d-none">
            <div class="bg-transparent no-shadow p-0">
                <div class="lds-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>

    </body>
</html>

