            <!-- begin sidebar user -->
            <ul class="nav">
                    <li class="nav-profile text-center">
                        <div class="nav-profile-image ">
                            @if ($picture == 'default')
                                <i class="fa fa-user-circle-o fa-4x" aria-hidden="true"></i>
                            @else
                                <img class="img-circle" src={{ $picture }}>
                            @endif
                             
                        </div>
                        <div class="nav-profile-info">
                            <small>{{ $employee['personnel_no'] }}</small>
                            <h5>{{ $employee['name'] }}</h5>
                            <h6 class="m-b-5">{{ $employee['position_name'] }}</h6>
                            <h6>{{ $employee['org_unit_name'] }}</h6>
                        </div>
                    </li>
                </ul>
                <!-- end sidebar user -->