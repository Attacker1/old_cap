{{-- Aside --}}

@php
    $kt_logo_image = 'logo-light.png';
@endphp

@if (config('layout-m.brand.self.theme') === 'light')
    @php $kt_logo_image = 'logo-dark.png' @endphp
@elseif (config('layout-m.brand.self.theme') === 'dark')
    @php $kt_logo_image = 'logo-light.png' @endphp
@endif

<div class="aside aside-left {{ App\Classes\Theme\Metronic::printClasses('aside', false) }} d-flex flex-column flex-row-auto" id="kt_aside">

    {{-- Brand --}}
    <div class="brand flex-column-auto {{ App\Classes\Theme\Metronic::printClasses('brand', false) }}" id="kt_brand">
        <div class="brand-logo">
            <a href="{{ url('/') }}">
                <img 
                    src="{{asset('app-assets/images/capsula/logo-capsula.png')}}">
                <span>Capsula</span>
            </a>
        </div>

        @if (config('layout-m.aside.self.minimize.toggle'))
            <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                {{ App\Classes\Theme\Metronic::getSVG("m/media/svg/icons/Navigation/Angle-double-left.svg", "svg-icon-xl") }}
            </button>
        @endif

    </div>

    {{-- Aside menu --}}
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        @if (config('layout-m.aside.self.display') === false)
            <div class="header-logo">
                <a href="{{ url('/') }}">
                    <img alt="{{ config('app.name') }}" src="{{ asset('m/media/logos/'.$kt_logo_image) }}"/>
                </a>
            </div>
        @endif

        <div
            id="kt_aside_menu"
            class="aside-menu my-4 {{ App\Classes\Theme\Metronic::printClasses('aside_menu', false) }}"
            data-menu-vertical="1"
            {{ App\Classes\Theme\Metronic::printAttrs('aside_menu') }}>

            <ul class="menu-nav {{ App\Classes\Theme\Metronic::printClasses('aside_menu_nav', false) }}">
                {{ App\Classes\Theme\Menu::renderVerMenu(config('menu_aside-m.items')) }}
            </ul>
        </div>
    </div>

</div>
