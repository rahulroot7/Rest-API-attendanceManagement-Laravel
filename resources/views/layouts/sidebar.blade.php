<aside class="app-sidebar">
    <div class="side-header p-2">
        <a class="header-brand1" href="index.html">
            <img src="{{ asset('img/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ asset('img/logo.png') }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ asset('img/logo.png') }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ asset('img/logo.png') }}" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li>
            <h3>Main</h3>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="{{asset('dashboard')}}">
                <i class="side-menu__icon fe fe-home"></i>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>

        @can('isAdmin')
            <!-- Master Management -->
            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <i class="side-menu__icon fe fe-sliders"></i>
                    <span class="side-menu__label">Master</span><i class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li><a class="sub-slide-item" href="{{ route('departments.index') }}">All Departments</a></li>
                    <li><a class="sub-slide-item" href="{{ route('designations.index') }}">All Designations</a></li>
                    <li><a class="sub-slide-item" href="{{ route('states.index') }}">All States</a></li>
                    <li><a class="sub-slide-item" href="{{ route('cities.index') }}">All Cities</a></li>
                    <li><a class="sub-slide-item" href="{{ route('holidays.index') }}">All Holidays</a></li>
                </ul>
            </li>
        @endcan


        <!-- Employee Management -->
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="#">
                <i class="side-menu__icon fe fe-sliders"></i>
                <span class="side-menu__label">Employee Management</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="sub-slide-item" href="{{ route('employees.index') }}">All Employees</a></li>
                @can('create', \App\Models\Employee::class)
                    <li><a class="sub-slide-item" href="{{ route('employees.create') }}">Create Employee</a></li>
                    <li><a class="sub-slide-item" href="{{ route('imports.index') }}">Import Employee</a></li>
                @endcan
            </ul>
        </li>

        <!-- Attendance Management -->
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="#">
                <i class="side-menu__icon fe fe-sliders"></i>
                <span class="side-menu__label">Attendance Management</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="sub-slide-item" href="{{ route('attendances.index') }}">Attendance Report</a></li>
            </ul>
        </li>

        <!-- Target Management -->
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="#">
                <i class="side-menu__icon fe fe-sliders"></i>
                <span class="side-menu__label">Target Management</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="sub-slide-item" href="{{ route('targets.index') }}">All Targets</a></li>
            </ul>
        </li>

        <!-- Attendance Management -->
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="#">
                <i class="side-menu__icon fe fe-sliders"></i>
                <span class="side-menu__label">Change Request</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="sub-slide-item" href="{{ route('changeRequests.index') }}">All Change Request</a></li>
            </ul>
        </li>

        <!-- Reports -->
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="#">
                <i class="side-menu__icon fe fe-sliders"></i>
                <span class="side-menu__label">Reports</span>
                <i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="sub-slide-item" href="{{ route('reports.field') }}">Field Report</a></li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="side-menu__icon fe fe-home"></i>
                <span class="side-menu__label">logout</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </a>
        </li>
        <!-- end sub menus -->
    </ul>
</aside>
