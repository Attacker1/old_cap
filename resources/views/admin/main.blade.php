@include('admin.layout.header')
<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  @yield('navbar', '')" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

<!-- BEGIN: Header-->
@include('admin.layout.topnav')
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
@include('admin.layout.leftmenu')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
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
            <section id="grid-options" class="row">
                <div class="@yield('maincol', 'col-md-12')">
                    <div class="card">
                       @yield('title_gray','')
                        <div class="card-header row">
                            <div class="col-12">
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Content end -->

        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
@include('admin.layout.footer')

@yield('scripts')
