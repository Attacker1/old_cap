<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('admin-clients.orders.list') }}">
                    <div class="brand-logo"></div>
                    <h2 class="brand-text mb-0">Capsula</h2>
                </a></li>
        </ul>
    </div>
    <!--<div class="shadow-bottom"></div>-->
    <div class="main-menu-content" style="margin-top:20px">
        <ul style="display: flex; flex-direction: column; height: 100%" class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                
            <li>
                <a href="{{ route('admin-clients.orders.list') }}" class="orders-button">
                    <i class="feather icon-layers"></i>
                    <span class="menu-title" data-i18n="Dashboard">Заказы</span>
                </a>
            </li>

            <li class="nav-item menu-collapsed-open">
                <a href="{{route('admin-clients.bonuses.index')}}" class="bonuses-btn">
                    <i class="feather icon-gift"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">Бонусы</span>
                    <span class="badge badge-light-success badge-pill ml-auto mr-1 mt-1">
                        <b>{{ auth()->guard('admin-clients')->user()->bonuses->points ?? 0}}</b></span>
                </a>
            </li>

            <li>
                <a href="{{route('admin-clients.anketa.show')}}" class="btn-anketa">
                    <i class="far fa-id-badge"></i>
                    <span class="menu-title" data-i18n="Dashboard">Анкета</span>
                </a>
            </li>

            <li>
                <div><hr></div>
            </li>

            <li>
                <a href="https://api.whatsapp.com/send/?phone=78007000762&text&app_absent=0" target="_blank" class="manager-chat-button">
                    <i class="fab fa-whatsapp-square text-success"></i>
                    <p>Чат с менеджером <br><span style="margin-left: 33px">в Whatsapp</span></p>


                </a>
            </li>

            <li style="height:100%; display: flex; flex-direction: column; justify-content: flex-end;" class="mb-2">
                <a  class="nav-link left-menu-logout btn-exit-lk" href="{{ route('admin-clients.auth.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Выйти
                </a>
                <form id="logout-form" action="{{ route('admin-clients.auth.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</div>
