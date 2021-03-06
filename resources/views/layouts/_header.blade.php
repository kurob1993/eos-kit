    <div id="header" class="header navbar navbar-default navbar-fixed-top">
        <!-- begin container-fluid -->
        <div class="container-fluid">
            <!-- begin mobile sidebar expand / collapse button -->
            <div class="navbar-header navbar-header-without-bg">
                <a href={{ url("/") }} class="navbar-brand">
                        <span>
                            <img width="26px" src={{ url("/images", ['krakatausteel-logo.png'])  }}>
                        </span>
                        <span>&nbsp; {{ config('app.name') }}</span>
                    </a>
                <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
            </div>
            <!-- end mobile sidebar expand / collapse button -->

            <!-- begin header navigation right -->
            <ul class="nav navbar-nav navbar-right text-center">
                <li class="dropdown">
                    <a href={{ route('help.index') }}>
                        <strong>Bantuan</strong>&nbsp;&nbsp;<i class="fa fa-question-circle"></i>
                    </a>
                </li>
            </ul>
            <!-- end header navigation right -->
        </div>
        <!-- end container-fluid -->
    </div>