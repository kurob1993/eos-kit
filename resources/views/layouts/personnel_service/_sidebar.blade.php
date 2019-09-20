<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">

        @include('layouts._sidebar-user')

        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header">Personnel Service</li>

            @role('personnel_service')
            <li class="has-sub {{ 
                    (
                        (Request::segment(1) == 'internal-activity')||
                        (Request::segment(1) == 'external-activity')
                    ) 
                    ? 'active' : '' 
                    }}">
                <a href="javascript:;">
                    <b class="caret pull-right"></b>
                    <i class="icon-user"></i>
                    <span>Personnel Admin</span>
                </a>
                <ul class="sub-menu">
                    <li class="{{ Request::segment(1)=='internal-activity' ? 'active' : '' }}">
                        <a href="{{ route('personnel_service.internal-activity.index') }}">Internal Activity</a>
                    </li>
                    <li class="{{ Request::segment(1)=='external-activity' ? 'active' : '' }}">
                        <a href="{{ route('personnel_service.external-activity.index') }}">External Activity</a>
                    </li>
                </ul>
            </li>

            <li class="has-sub" {{ (
                    (Request::segment(1) == 'all_leaves') || (Request::segment(1) == 'all_absence_quotas') 
                        || (Request::segment(1) == 'all_permits/absence') || (Request::segment(1) == 'all_permits/attendance') 
                        || (Request::segment(1) == 'all_time_events') || (Request::segment(1) == 'all_overtimes') 
                        ) ? 'active' : '' 
                    }}>
                <a href="javascript:;">
                    <b class="caret pull-right"></b>
                    <i class="fa fa-inbox"></i>
                    <span>Time Management</span>
                </a>
                <ul class="sub-menu">
                    <li> <a href="{{ route('all_leaves.index') }}">Cuti</a> </li>
                    <li> <a href="{{ route('all_permits.absence') }}">Izin (Absence)</a> </li>
                    <li> <a href="{{ route('all_permits.attendance') }}">Izin (Attendance)</a> </li>
                    <li> <a href="{{ route('all_time_events.index') }}">Tidak Slash</a> </li>
                    <li> <a href="{{ route('all_overtimes.index') }}">Lembur</a> </li>
                    <li> <a href="{{ route('all_absence_quotas.index') }}">Kuota Cuti</a> </li>
                    <li> <a href="{{ route('sendtosap.absence.index') }}">Send To SAP</a> </li>
                    <li> <a href="{{ route('transition.index') }}">Pengalihan</a> </li>
                </ul>
            </li>
            @endrole

            <li class="">
                <a href="{{ route('home') }}">
                    <i class="fa fa-sign-out"></i>
                    <span>Kembali ke HCI</span>
                </a>
            </li>
            <!-- begin sidebar minify button -->
            <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>