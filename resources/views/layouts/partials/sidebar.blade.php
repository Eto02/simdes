<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">SIMDES</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="{{ asset('lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image"> --}}
                <img src="{{ route('view.logo') }}/{{ auth()->user()->id }}" class="img-circle elevation-2 p-1" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        @php
            $currentRoute = url()->current();
            $dashboardActive = '';
            $serviceActive = '';
            $reportActive = '';

            $employeeActive = '';
            $serviceTypeActive = '';
            $fileTypeActive = '';

            switch ($currentRoute) {
                case route('dashboard'):
                    $dashboardActive = 'active';
                    break;
                case route('service'):
                    $serviceActive = 'active';
                    break;
                case route('report'):
                    $reportActive = 'active';
                    break;
                case route('employee'):
                    $employeeActive = 'active';
                    break;
                case route('service_type'):
                    $serviceTypeActive = 'active';
                    break;
                case route('file_type'):
                    $fileTypeActive = 'active';
                    break;
                default:
                    break;
            }
        @endphp

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ $dashboardActive }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (auth()->user()->roles[0]->name == 'superadmin' || auth()->user()->roles[0]->name == 'employee')
                <li class="nav-item">
                    <a href="{{ route('service') }}" class="nav-link {{ $serviceActive }}">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Layanan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('report') }}" class="nav-link {{ $reportActive }}">
                        <i class="nav-icon fas fa-print"></i>
                        <p>
                            Laporan
                        </p>
                    </a>
                </li>
                @endif
                {{-- <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Company</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis Layanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis Surat</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                <li class="nav-header">MASTER</li>
                @if (auth()->user()->roles[0]->name == 'superadmin')
                <li class="nav-item">
                    <a href="{{ route('employee') }}" class="nav-link {{ $employeeActive }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Company
                        </p>
                    </a>
                </li>
                @endif
                @if (auth()->user()->roles[0]->name == 'employee')
                <li class="nav-item">
                    <a href="{{ route('service_type') }}" class="nav-link {{ $serviceTypeActive }}">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p>
                            Jenis Layanan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('file_type') }}" class="nav-link {{ $fileTypeActive }}">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p>
                            Jenis Berkas
                        </p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
