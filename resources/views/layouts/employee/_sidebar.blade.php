<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">

    @include('layouts._sidebar-user')

        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header">Navigation</li>

            @role('employee')
            <li class="{{ Route::current()->getName() == 'dashboards.employee' ? 'active' : '' }}">
                <a href="{{ route('dashboards.employee') }}">
                    @if ($count_of_needed_approvals > 0) 
                    <span class="badge pull-right">
                        {{$count_of_needed_approvals}} 
                    </span>
                    @endif
                    <i class="fa fa-inbox"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="has-sub">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="icon-user"></i>
                        <span>Personnel Admin</span>
                    </a>
                <ul class="sub-menu">
                    <li> <a href="#">Curriculum Vitae</a> </li>
                    <li> <a href="#">Data Karyawan</a> </li>
                    <li> <a href="#">Organisasi</a> </li>
                    <li> <a href="#">Tugas Pokok Fungsi</a> </li>
                </ul>
            </li>
            <li class="has-sub {{ Request::segment(1)=='leaves' ? 'active' : '' }}">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-inbox"></i>
                        <span>Time Management</span>
                    </a>
                <ul class="sub-menu">
                    <li class="{{ Request::segment(1)=='leaves' ? 'active' : '' }}">
                        <a href="{{ url('leaves') }}">Cuti</a>
                    </li>
                    <li> <a href="#">Izin</a> </li>
                    <li> <a href="#">Lembur</a> </li>
                    <li> <a href="#">Waktu Kerja</a> </li>
                    <li> <a href="#">Laporan Aktivitas</a> </li>
                </ul>
            </li>
            <li class="has-sub">
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
            </li>

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