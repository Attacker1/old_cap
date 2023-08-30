@if(config('layout-m.self.layout') == 'blank')
    <div class="d-flex flex-column flex-root">
        @yield('content')
    </div>
@else

    @include('admin.layout-metronic.base._header-mobile')

    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">

            @if(config('layout-m.aside.self.display'))
                @include('admin.layout-metronic.base._aside')
            @endif

            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                @include('admin.layout-metronic.base._header')

                <div class="content {{ App\Classes\Theme\Metronic::printClasses('content', false) }} d-flex flex-column flex-column-fluid" id="kt_content" style="">

                    @if(config('layout-m.subheader.display'))
                        @if(array_key_exists(config('layout-m.subheader.layout'), config('layout-m.subheader.layouts')))
                            @include('admin.layout-metronic.partials.subheader._'.config('layout-m.subheader.layout'))
                        @else
                            @include('admin.layout-metronic.partials.subheader._'.array_key_first(config('layout-m.subheader.layouts')))
                        @endif
                    @endif

                    @include('admin.layout-metronic.base._content')
                </div>

                @include('admin.layout-metronic.base._footer')
            </div>
        </div>
    </div>

@endif

@if (config('layout-m.self.layout') != 'blank')

    @if (config('layout-m.extras.search.layout') == 'offcanvas')
        @include('admin.layout-metronic.partials.extras.offcanvas._quick-search')
    @endif

    @if (config('layout-m.extras.notifications.layout') == 'offcanvas')
        @include('admin.layout-metronic.partials.extras.offcanvas._quick-notifications')
    @endif

    @if (config('layout-m.extras.quick-actions.layout') == 'offcanvas')
        @include('admin.layout-metronic.partials.extras.offcanvas._quick-actions')
    @endif

    @if (config('layout-m.extras.user.layout') == 'offcanvas')
        @include('admin.layout-metronic.partials.extras.offcanvas._quick-user')
    @endif

    @if (config('layout-m.extras.quick-panel.display'))
        @include('admin.layout-metronic.partials.extras.offcanvas._quick-panel')
    @endif

    @if (config('layout-m.extras.toolbar.display'))
        @include('admin.layout-metronic.partials.extras._toolbar')
    @endif

    @if (config('layout-m.extras.chat.display'))
        @include('admin.layout-metronic.partials.extras._chat')
    @endif

    @include('admin.layout-metronic.partials.extras._scrolltop')

@endif
