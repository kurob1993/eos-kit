<div id="sidebar" class="sidebar">
        <!-- begin sidebar scrollbar -->
        <div data-scrollbar="true" data-height="100%">
    
            <!-- begin sidebar secretary -->
            <ul class="nav">
                    <li class="nav-profile text-center">
                        <div class="nav-profile-image ">
                            <img class="img-circle" src={{ Storage::url( 'default.png' ) }}> 
                        </div>
                        <div class="nav-profile-info">
                            <h6>Sekretaris</h6>
                            <h6>{{ $secretary['name'] }}</h6>
                            <h6 class="m-b-5">{{ $secretary['email'] }}</h6>
                        </div>
                    </li>
                </ul>
                <!-- end sidebar secretary -->
                    
            <!-- begin sidebar nav -->
            <ul class="nav">
                <li class="nav-header">Secretary</li>
    
                @role('secretary')
                <li class="has-sub {{ (
                    (Request::segment(2) == 'ski')
                ) ? 'active' : ''
                }}">
                    <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="icon-user"></i>
                            <span>Personnel Admin</span>
                        </a>
                    <ul class="sub-menu">
                        <li class="{{ Request::segment(2)=='ski' ? 'active' : '' }}">
                            <a href="{{ route('secretary.ski.index') }}">Sasaran Kinerja Individu</a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub {{ 
                    ( 
                        (Request::segment(2) == 'travels') || 
                        (Request::segment(2) == 'overtimes')
                    )  ? 'active' : '' 
                    }}">
                    <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-inbox"></i>
                            <span>Time Management</span>
                        </a>
                    <ul class="sub-menu">
                        {{-- <li> <a href="{{ route('secretary.travels.index') }}">Perjalanan Dinas</a> </li> --}}
                        <li class="{{ Request::segment(2)=='overtimes' ? 'active' : '' }}"> 
                            <a href="{{ route('secretary.overtimes.index') }}">Lembur</a> 
                        </li>
                    </ul>
                    
                </li>
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