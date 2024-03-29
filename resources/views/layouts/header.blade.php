<div class="header py-4">
    <div class="container">
        <div class="d-flex">
        <a class="header-brand" href="#" class="font-weight-bold">
            Back Office
            {{-- <img src="{{ asset('demo/brand/tabler.svg') }}" class="header-brand-img" alt="tabler logo"> --}}
        </a>
        <div class="d-flex order-lg-2 ml-auto">
            <div class="dropdown">
            <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                <span class="avatar avatar-blue"><i class="fe fe-user"></i></span>
                <span class="ml-2 d-none d-lg-block">
                <span class="text-default">{{Auth::user()->name}}</span>
                <small class="text-muted d-block mt-1">Administrator</small>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                {{-- <a class="dropdown-item" href="#">
                <i class="dropdown-icon fe fe-user"></i> Profile
                </a> --}}
                {{-- <a class="dropdown-item" href="#">
                <i class="dropdown-icon fe fe-settings"></i> Settings
                </a>
                <a class="dropdown-item" href="#">
                <span class="float-right"><span class="badge badge-primary">6</span></span>
                <i class="dropdown-icon fe fe-mail"></i> Inbox
                </a>
                <a class="dropdown-item" href="#">
                <i class="dropdown-icon fe fe-send"></i> Message
                </a> --}}
                <a class="dropdown-item" href="{{ route('auth.logout') }}">
                <i class="dropdown-icon fe fe-log-out"></i> ออกจากระบบ
                </a>
            </div>
            </div>
        </div>
        <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
            <span class="header-toggler-icon"></span>
        </a>
        </div>
    </div>
</div>