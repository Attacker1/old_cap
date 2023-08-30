{{-- Footer --}}

<div class="footer bg-white py-4 d-flex flex-lg-column {{ App\Classes\Theme\Metronic::printClasses('footer', false) }}" id="kt_footer">
    {{-- Container --}}
    <div class="{{ App\Classes\Theme\Metronic::printClasses('footer-container', false) }} d-flex flex-column flex-md-row align-items-center justify-content-between">
        {{-- Copyright --}}
        <div class="text-dark order-2 order-md-1">
            
        </div>

        @php
        // TODO: Временное до решения ошибки сервера
        $path = explode("/",app_path());
        @endphp
        {{-- Nav --}}
        <div class="nav nav-dark order-1 order-md-2">
            <span class="text-muted font-weight-bold mr-2">2020© {!! @$path[4] !!}</span>
            <a href="/" target="_blank" class="text-dark-75 text-hover-primary">Capsula</a>

        </div>
    </div>
</div>
