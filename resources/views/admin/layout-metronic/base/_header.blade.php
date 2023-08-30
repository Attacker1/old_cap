{{-- Header --}}
<div id="kt_header" class="header {{ App\Classes\Theme\Metronic::printClasses('header', false) }}" {{ App\Classes\Theme\Metronic::printAttrs('header') }}>

    {{-- Container --}}
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        @if (config('layout-m.header.self.display'))

            {{-- Header Menu --}}
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                @if(config('layout-m.aside.self.display') == false)
                    <div class="header-logo">
                        <a href="{{ url('/') }}">
                            <img alt="Logo" src="{{ asset('m/media/logos/'.$kt_logo_image) }}"/>
                        </a>
                    </div>
                @endif

                <div id="kt_header_menu" class="header-menu header-menu-mobile {{ App\Classes\Theme\Metronic::printClasses('header_menu', false) }}" {{ App\Classes\Theme\Metronic::printAttrs('header_menu') }}>
                    <ul class="menu-nav {{ App\Classes\Theme\Metronic::printClasses('header_menu_nav', false) }}">
                        {{ App\Classes\Theme\Menu::renderHorMenu(config('menu_header-m.items')) }}
                    </ul>
                </div>
            </div>
            <div class="topbar">
                <div class="topbar-item">
                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">{{ auth()->guard('admin')->user()->roles()->first()->name ?? 'Нет роли'  }}</span>
                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ auth()->guard('admin')->user()->name }}</span>
											<span class="symbol-label font-size-h5 font-weight-bold"><i class="flaticon-users "></i></span>

                    </div>
                </div>
            </div>

        @else
            <div></div>
        @endif

        @include('admin.layout-metronic.partials.extras._topbar')
    </div>
</div>
