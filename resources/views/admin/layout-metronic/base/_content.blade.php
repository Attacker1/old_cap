{{-- Content --}}
@if (config('layout-m.content.extended'))
    @yield('content')
@else
    <div class="d-flex flex-column-fluid">
        <div class="{{ App\Classes\Theme\Metronic::printClasses('content-container', false) }}">
            @yield('content')
        </div>
    </div>
@endif
