<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">

    @include('layouts._sidebar-user')

        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header">Navigation</li>

            @role('basis')
            <li class="has-sub">
                <a href="javascript:;">
                        <b class="caret pull-right"></b>
                        <i class="icon-user"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                <ul class="sub-menu">
                    <li> <a href="#">Hak Akses</a> </li>
                </ul>
            </li>
            <li class="">
                <a href="{{ route('settings') }}">
                        <i class="fa fa-gears"></i>
                        <span>Konfigurasi</span>
                    </a>
            </li>
            @endrole 

            <li class="">
                <a href="{{ route('dashboards.employee') }}" >
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