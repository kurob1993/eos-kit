<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">

    @include('layouts._sidebar-user')

        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header">Navigation</li>

            @role('employee')
            <li class="{{ Route::current()->getName() == 'dashboard.index' ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ Route::current()->getName() == 'dashboards.approval' ? 'active' : '' }}">
                <a href="{{ route('dashboards.approval') }}">
                    <i class="fa fa-check-circle"></i>
                    <span>Approval</span>
                </a>
            </li>            
            <li class="has-sub {{ 
                (
                    (Request::segment(1) == 'cvs') || 
                    (Request::segment(1) == 'organizations') || 
                    (Request::segment(1) == 'administrations') ||
                    (Request::segment(1) == 'internal-activity')
                ) ? 'active' : '' 
                }}">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="icon-user"></i>
                        <span>Personnel Admin</span>
                    </a>
                <ul class="sub-menu">
                    <li class="{{ Request::segment(1)=='cvs' ? 'active' : '' }}"> 
                        <a href="{{ url('cvs') }}">Curriculum Vitae</a> 
                    </li>

                    <li class="{{ Request::segment(1)=='internal-activity' ? 'active' : '' }}"> 
                        <a href="{{ route('internal-activity.index') }}">Internal Activity</a> 
                    </li>
                    {{-- <li class="{{ Request::segment(1)=='organizations' ? 'active' : '' }}"> 
                        <a href="#">Organisasi</a> 
                    </li>
                    <li class="{{ Request::segment(1)=='administrations' ? 'active' : '' }}"> 
                        <a href="#">Administrasi</a> 
                    </li> --}}
                </ul>
            </li>
            <li class="has-sub {{ (
            (Request::segment(1) == 'leaves') || (Request::segment(1) == 'permits') 
                || (Request::segment(1) == 'time_events') || (Request::segment(1) == 'overtimes') 
                || (Request::segment(1) == 'wakers') ) ? 'active' : '' 
            }}">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-inbox"></i>
                        <span>Time Management</span>
                    </a>
                <ul class="sub-menu">
                    <li class="{{ Request::segment(1)=='leaves' ? 'active' : '' }}">
                        <a href="{{ url('leaves') }}">Cuti</a>
                    </li>
                    <li class="{{ Request::segment(1)=='permits' ? 'active' : '' }}">
                        <a href="{{ url('permits') }}">Izin</a>
                    </li>
                    <li class="{{ Request::segment(1)=='time_events' ? 'active' : '' }}">
                        <a href="{{ url('time_events') }}">Tidak Slash</a>
                    </li>
                    <li class="{{ Request::segment(1)=='overtimes' ? 'active' : '' }}">
                        <a href="{{ url('overtimes') }}">Lembur</a>
                    </li>
                    <li class="{{ Request::segment(1)=='wakers' ? 'active' : '' }}"> 
                        <a href="{{ url('wakers') }}">Waktu Kerja</a> 
                    </li>
                    <li> <a href="{{ route('activity.index') }}">Laporan Aktivitas</a> </li>
                </ul>
            </li>
            {{-- <li class="has-sub">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-suitcase"></i>
                        <span>Payroll &amp; Benefit</span>
                    </a>
                <ul class="sub-menu">
                    <li> <a href="#">Payslip</a> </li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-suitcase"></i>
                        <span>Health &amp; Safety</span>
                    </a>
                <ul class="sub-menu">
                    <li> <a href="#">Medical Check-up</a> </li>
                </ul>
            </li> --}}

            {{-- route to dashboard basis from employee sidebar --}} 
            @role(['basis'])
            <li class="">
                <a href="{{ route('dashboards.basis') }}">
                    <i class="fa fa-gears"></i>
                    <span>Basis</span>
                </a>
            </li>
            @endrole 

            {{-- route to dashboard personnel_service from employee sidebar --}} 
            @role(['personnel_service'])
            <li class="">
                <a href="{{ route('dashboards.personnel_service') }}">
                    <i class="fa fa-gears"></i>
                    <span>Personnel Services</span>
                </a>
            </li>
            @endrole 

            @endrole

            <li class="">
                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i>
                    <span>Kembali ke SSO</span>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            <!-- begin sidebar minify button -->
            <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>