<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('admin.main.index') }}">
                    <div class="brand-logo"></div>
                    <h2 class="brand-text mb-0">Capsula</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
        </ul>
    </div>
    <!--<div class="shadow-bottom"></div>-->
    <div class="main-menu-content" style="margin-top:20px">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li  @if(Route::is('admin.main.index') ) class="active" @endif>
                <a href="{{ route('admin.main.index') }}"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Главная</span></a>

            @if(auth()->guard('admin')->user()->can('view-notes'))
                <li class=" nav-item  @if(Route::is('notes.index') ) active @endif">
                    <a href="{{ route('notes.list') }}"><i class="feather icon-book-open"></i><span class="menu-item" data-i18n="Shop">Записка стилиста</span></a>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->can('catalog-manage'))
                <li  @if(Route::is('admin.catalog.products.index') ) class="active" @endif><a href="{{ route('admin.catalog.products.index') }}">
                        <i class="feather icon-layers"></i><span class="menu-item" data-i18n="Products">Товары</span></a>
                </li>
            @endif

            @if(auth()->guard('admin')->user()->can('view-leads') && config('app.env') != 'production')
                <li  @if(Route::is('leads.list') ) class="active" @endif><a href="{{ route('leads.list') }}">
                        <i class="fa fa-handshake-o" aria-hidden="true">Сделки</i></a>
                </li>
            @endif

            @if(auth()->guard('admin')->user()->can('view-payments')  && config('app.env') != 'production')
                <li  @if(Route::is('payments.list') ) class="active" @endif><a href="{{ route('payments.list') }}">
                        <i class="feather icon-dollar-sign"></i><span class="menu-item" data-i18n="dollar-sign">Оплаты</span></a>
                </li>
            @endif


            @if(auth()->guard('admin')->user()->can('catalog-manage'))
                <li class=" navigation-header"><span>Настройки Каталога</span></li>
                <ul class="menu-content">
                    <li @if(Route::is('admin.catalog.brands.index') ) class="active" @endif><a href="{{ route('admin.catalog.brands.index') }}">
                            <i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Бренды</span></a>
                    </li>
                    <li @if(Route::is('admin.catalog.categories.index') ) class="active" @endif><a href="{{ route('admin.catalog.categories.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Категории</span></a>
                    </li>

                    <li  @if(Route::is('admin.catalog.attributes.index') ) class="active" @endif><a href="{{ route('admin.catalog.attributes.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Details">Характеристики</span></a>
                    </li>
                    <li  @if(Route::is('admin.catalog.presets.index') ) class="active" @endif><a href="{{ route('admin.catalog.presets.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Пресеты</span></a>
                    </li>
                    <li  @if(Route::is('admin.color.index') ) class="active" @endif><a href="{{ route('admin.color.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Цвета</span></a>
                    </li>
                </ul>
            @endif

            @if(auth()->guard('admin')->user()->can('manage-clients'))
                <li  class="nav-item">
                    <a href="#"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Shop">Клиенты</span></a>
                    <ul class="menu-content">
                        <li @if(Route::is('clients.list') ) class="active" @endif>
                            <a href="{{ route('clients.list') }}">
                                <i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Список</span></a></li>
                        <li @if(Route::is('admin.clients-statuses.list') ) class="active" @endif>
                            <a href="{{ route('admin.clients-statuses.list') }}">
                                <i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Статусы</span></a></li>

                        <li @if(Route::is('bonuses.index') ) class="active" @endif>
                            <a href="{{ route('bonuses.index') }}">
                                <i class="feather icon-circle"></i><span class="menu-item" data-i18n="Money">Бонусы</span></a></li>
                    </ul>
                </li>
            @endif


            @if(auth()->guard('admin')->user()->can('viewing-feedback-list'))
                <li class="nav-item">
                    <a href="{{ route('admin.feedback.list.fill') }}">
                        <i class="fa fa-comments-o" aria-hidden="true"></i>
                        <span class="menu-item" data-i18n="Shop">Обратная связь</span>
                    </a>
                </li>
            @endif
            @if(auth()->guard('admin')->user()->can('viewing-anketa-list'))
                <li class="nav-item">
                    <a href="{{ route('admin.anketa.list.fill') }}">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                        <span class="menu-item" data-i18n="Shop">Анкеты</span>
                    </a>
                </li>
            @endif

            @if(auth()->guard('admin')->user()->can('manage-users'))
            <li class=" navigation-header"><span>Система</span></li>
            <li class=" nav-item"><a href="#"><i class="feather icon-settings"></i><span class="menu-title" data-i18n="Ecommerce">Настройки</span></a>
                <ul class="menu-content">
                    <li @if(Route::is('admin.manage.users.index') ) class="active" @endif><a href="{{ route('admin.manage.users.index') }}"><i class="feather icon-user"></i><span class="menu-item" data-i18n="Shop">Пользователи</span></a>
                    </li>
                    <li  @if(Route::is('manage.roles.index') ) class="active" @endif><a href="{{ route('manage.roles.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="Shop">Права и роли</span></a>
                    </li>
                </ul>
            </li>
            @endif


        </ul>
    </div>
</div>
