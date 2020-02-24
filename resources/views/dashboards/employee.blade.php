@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Employee Dashboard'])
<div class="row">
    <div class="col-lg-12 col-xl-9">
        <h4>Persetujuan Karyawan</h4>
        @include('layouts._flash')
        <!-- begin of dashboard nav-tabs  -->
        <ul class="nav nav-tabs nav-tabs-primary nav-justified nav-justified-mobile">
            <li class="active">
                <a href="#tab-leaves" data-toggle="tab" aria-expanded="true"> Cuti
                    @if ($countLeaveApprovals > 0)
                    <span class="badge pull-right m-l-5">
                        {{$countLeaveApprovals}}
                    </span>
                    @endif
                </a>
            </li>
            <li class="">
                <a href="#tab-permits" data-toggle="tab" aria-expanded="true"> Izin
                    @if ($countPermitApprovals > 0)
                    <span class="badge pull-right m-l-5">
                        {{$countPermitApprovals}}
                    </span>
                    @endif
                </a>
            </li>
            <li class="">
                <a href="#tab-time-events" data-toggle="tab" aria-expanded="true"> Slash
                    @if ($countTimeEventApprovals > 0)
                    <span class="badge pull-right m-l-5">
                        {{$countTimeEventApprovals}}
                    </span>
                    @endif
                </a>
            </li>
            <li class="">
                <a href="#tab-overtimes" data-toggle="tab" aria-expanded="true"> Lembur
                    @if ($countOvertimeApprovals > 0)
                    <span class="badge pull-right m-l-5">
                        {{$countOvertimeApprovals}}
                    </span>
                    @endif
                </a>
            </li>
        </ul>
        <!-- end of dashboard nav-tabs  -->

        <!-- begin of tab-content  -->
        <div class="tab-content">
            <!-- begin of leaves tab  -->
            <div class="tab-pane fade active in" id="tab-leaves">
                <div class="panel-body p-0">
                    <p>
                        {{-- <a class="btn btn-primary" href="{{ route('dashboards.approve_all', ['approval' => 'leaves']) }}"> Setujui Semua </a>
                        <a class="btn btn-danger" href="{{ route('dashboards.reject_all', ['approval' => 'leaves']) }}"> Tolak Semua </a> --}}
                    </p>
                    <div id="leave-chart" class="m-t-5 m-b-5">Fusionchart for leaves will be rendered here.</div>
                    <div class="table-responsive">
                        {!! $leaveTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
                    </div>
                </div>
            </div>
            <!-- end of leaves tab  -->

            <!-- begin of permits tab  -->
            <div class="tab-pane fade" id="tab-permits">
                <div class="panel-body p-0">
                    <div id="permit-chart" class="m-t-5 m-b-5">Fusionchart for permits will be rendered here.</div>
                    <div class="table-responsive">
                        {!! $permitTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
                    </div>
                </div>
            </div>
            <!-- end of permits tab  -->

            <!-- begin of time-events tab  -->
            <div class="tab-pane fade" id="tab-time-events">
                <div class="panel-body p-0">
                    <div id="time-event-chart" class="m-t-5 m-b-5">Fusionchart for time events will be rendered here.</div>
                    <div class="table-responsive">
                        {!! $timeEventTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
                    </div>
                </div>
            </div>
            <!-- end of time-events tab  -->

            <!-- begin of overtimes tab  -->
            <div class="tab-pane fade" id="tab-overtimes">
                <div class="panel-body p-0">
                    {!! $overtimeTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
                </div>
            </div>
            <!-- end of overtimes tab  -->

            <!-- Begin Modal pengumuman-->
            <div id="pengumuman" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">
                                <i class="fa fa-volume-up"></i>
                                PENGUMUMAN
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <img src="{{ url('/images/pengumuman.jpg') }}" alt="" width="100%">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Keluar</button>
                        </div>
                    </div>
                </div>  
            </div>
            <!-- end Modal pengumuman-->

        </div>
        <!-- begin of tab-content  -->
    </div>
</div>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Persetujuan (ID: <span id="title-span"></span>)</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/jquery.dataTables.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Responsive/css/responsive.dataTables.min.css") }} rel="stylesheet" />
<!-- Selectize -->
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
<!-- Pace -->
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- Fusion Chart -->
<script src="{{ url("/plugins/fusioncharts/js/fusioncharts.js") }}"></script>
<script src="{{ url("/plugins/fusioncharts/js/fusioncharts.charts.js") }}"></script>
<script src="{{ url("/plugins/fusioncharts/js/fusioncharts.overlappedbar2d.js") }}"></script>
<script src="{{ url("/plugins/fusioncharts/js/themes/fusioncharts.theme.fusion.js") }}"></script>
{{-- {!! $leaveChart->render() !!}
{!! $permitChart->render() !!}
{!! $timeEventChart->render() !!} --}}
<!-- Selectize -->
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $leaveTable->scripts() !!}
{!! $permitTable->scripts() !!}
{!! $overtimeTable->scripts() !!}
{!! $timeEventTable->scripts() !!}
@endpush

@push('custom-scripts')
@include('scripts._defer-ajax-dt-script')
@include('scripts._dashboard-script', [ 'stages' => $stages, 'tableNames' => $tableNames ])
@include('scripts._save-tab-state-script')
@endpush

@push('on-ready-scripts')
DashboardPlugins.init();
TabStatePlugins.init();
@endpush