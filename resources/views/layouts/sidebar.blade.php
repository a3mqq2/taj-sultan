<div id="sidenav-menu">
    <div class="side-nav">
        <li class="side-nav-item">
            <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                <span class="menu-text">الرئيسية</span>
            </a>
        </li>

        <li class="side-nav-item">
            <a href="{{ route('products.index') }}" class="side-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="ti ti-package"></i></span>
                <span class="menu-text">الأصناف</span>
            </a>
        </li>

        <li class="side-nav-item">
            <a href="{{ route('special-orders.index') }}" class="side-nav-link {{ request()->routeIs('special-orders.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="ti ti-cake"></i></span>
                <span class="menu-text">الطلبيات الخاصة</span>
            </a>
        </li>



        @if(auth()->user()->hasPermission('reports.view'))
        <li class="side-nav-item">
            <a href="{{ route('reports.sales.index') }}" class="side-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <span class="menu-icon"><i class="ti ti-chart-bar"></i></span>
                <span class="menu-text">التقارير</span>
            </a>
        </li>
        @endif

        <li class="side-nav-item">
            <a class="side-nav-link {{ request()->routeIs('payment-methods.*') || request()->routeIs('users.*') || request()->routeIs('pos-points.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="{{ request()->routeIs('payment-methods.*') || request()->routeIs('users.*') || request()->routeIs('pos-points.*') ? 'true' : 'false' }}" aria-controls="settingsMenu">
                <span class="menu-icon"><i class="ti ti-settings"></i></span>
                <span class="menu-text">الإعدادات</span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse {{ request()->routeIs('payment-methods.*') || request()->routeIs('users.*') || request()->routeIs('pos-points.*') ? 'show' : '' }}" id="settingsMenu">
                <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('payment-methods.index') }}" class="side-nav-link {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}">
                            <span class="menu-text">طرق الدفع</span>
                        </a>
                    </li>
                    @if(auth()->user()->hasPermission('pos_point.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('pos-points.index') }}" class="side-nav-link {{ request()->routeIs('pos-points.*') ? 'active' : '' }}">
                            <span class="menu-text">نقاط البيع</span>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('user.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('users.index') }}" class="side-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <span class="menu-text">المستخدمين</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>

        <li class="side-nav-item mt-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                @csrf
                <button type="submit" class="side-nav-link text-danger border-0 bg-transparent w-100 text-start">
                    <span class="menu-icon"><i class="ti ti-logout"></i></span>
                    <span class="menu-text">تسجيل الخروج</span>
                </button>
            </form>
        </li>
    </div>
</div>
