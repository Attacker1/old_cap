{{-- Header Mobile --}}
<div id="kt_header_mobile" class="header-mobile {{ App\Classes\Theme\Metronic::printClasses('header-mobile', false) }}" {{ App\Classes\Theme\Metronic::printAttrs('header-mobile') }}>
    <div class="mobile-logo">
    </div>
    <div class="d-flex align-items-center">

        @if (config('layout.aside.self.display'))
            <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle"><span></span></button>
        @endif

        @if (config('layout.header.menu.self.display'))
            <button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle"><span></span></button>
        @endif

        <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
            {{ App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/User.svg', 'svg-icon-xl') }}
        </button>

    </div>
</div>
