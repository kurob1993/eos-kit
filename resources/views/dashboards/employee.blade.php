@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Employee Dashboard'])
<div class="row">
    <div class="col-lg-12 col-xl-9">
        <h4>Dashboard</h4>
        @include('layouts._flash')
        <!-- begin of dashboard nav-tabs  -->
        <ul class="nav nav-tabs nav-tabs-primary nav-justified nav-justified-mobile">
            <li class="">
                <a href="#tab-leaves" data-toggle="tab" aria-expanded="true"> Cuti
                </a>
            </li>
            <li class="">
                <a href="#tab-permits" data-toggle="tab" aria-expanded="true"> Izin
                </a>
            </li>
            <li class="">
                <a href="#tab-time-events" data-toggle="tab" aria-expanded="true"> Slash
                </a>
            </li>
            <li class="">
                <a href="#tab-overtimes" data-toggle="tab" aria-expanded="true"> Lembur
                </a>
            </li>
        </ul>
        <!-- end of dashboard nav-tabs  -->

        <!-- begin of tab-content  -->
        <div class="tab-content">
            <!-- begin of leaves tab  -->
            <div class="tab-pane fade active in" id="tab-leaves">
                <div class="panel-body p-0">
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="filter-leave-boss">Atasan</label>
                            <select name="lfboss" id="filter-leave-boss" class="form-control"
                                onchange="leaveChartClickHandler()">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter-overtime-month">Bulan</label>
                            <select name="lfmonth" id="filter-leave-month" class="form-control"
                                onchange="leaveChartClickHandler()">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter-leave-year">Tahun</label>
                            <select name="lfyear" id="filter-leave-year" class="form-control"
                                onchange="leaveChartClickHandler()">

                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter-leave-day">Total Cuti</label>
                                <select name="lfday" id="filter-leave-day"
                                    class="form-control" onchange="">
                                    <option value="1">> 0 hari</option>
                                    <option value="2">Semua</option>
                                </select>
                            </div>
                        </div> --}}
                    <div id="leave-chart" class="m-t-5 m-b-5"></div>
                </div>
            </div>
            <!-- end of leaves tab  -->

            <!-- begin of permits tab  -->
            <div class="tab-pane fade" id="tab-permits">
                <div class="panel-body p-0">
                        <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="filter-permit-boss">Atasan</label>
                                    <select name="lfboss" id="filter-permit-boss" class="form-control"
                                        onchange="permitChartClickHandler()">
        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter-overtime-month">Bulan</label>
                                    <select name="lfmonth" id="filter-permit-month" class="form-control"
                                        onchange="permitChartClickHandler()">
        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter-permit-year">Tahun</label>
                                    <select name="lfyear" id="filter-permit-year" class="form-control"
                                        onchange="permitChartClickHandler()">
        
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter-permit-day">Total Cuti</label>
                                        <select name="lfday" id="filter-permit-day"
                                            class="form-control" onchange="">
                                            <option value="1">> 0 hari</option>
                                            <option value="2">Semua</option>
                                        </select>
                                    </div>
                                </div> --}}
                    <div id="permit-chart" class="m-t-5 m-b-5"></div>
                </div>
            </div>
            <!-- end of permits tab  -->

            <!-- begin of time-events tab  -->
            <div class="tab-pane fade" id="tab-time-events">
                <div class="panel-body p-0">
                        <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="filter-time-event-boss">Atasan</label>
                                    <select name="teboss" id="filter-time-event-boss" class="form-control"
                                        onchange="timeEventChartClickHandler()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter-time-event-month">Bulan</label>
                                    <select name="temonth" id="filter-time-event-month" class="form-control"
                                        onchange="timeEventChartClickHandler()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter-time-event-year">Tahun</label>
                                    <select name="teyear" id="filter-time-event-year" class="form-control"
                                        onchange="timeEventChartClickHandler()">
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter-time-event-year">Total Jam</label>
                                        <select name="ofhour" id="filter-time-event-hour"
                                            class="form-control" onchange="timeEventChartClickHandler()">
                                            <option value="1">> 0 jam</option>
                                            <option value="2">Semua</option>
                                        </select>
                                    </div>
                                </div> --}}
                    <div id="time-event-chart" class="m-t-5 m-b-5">
                    </div>
                </div>
            </div>
            <!-- end of time-events tab  -->

            <!-- begin of overtimes tab  -->
            <div class="tab-pane fade" id="tab-overtimes">
                <div class="panel-body p-0">
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="filter-overtime-boss">Atasan</label>
                            <select name="ofboss" id="filter-overtime-boss" class="form-control"
                                onchange="overtimeChartClickHandler()">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter-overtime-month">Bulan</label>
                            <select name="ofmonth" id="filter-overtime-month" class="form-control"
                                onchange="overtimeChartClickHandler()">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter-overtime-year">Tahun</label>
                            <select name="ofyear" id="filter-overtime-year" class="form-control"
                                onchange="overtimeChartClickHandler()">
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter-overtime-year">Total Jam</label>
                                <select name="ofhour" id="filter-overtime-hour"
                                    class="form-control" onchange="overtimeChartClickHandler()">
                                    <option value="1">> 0 jam</option>
                                    <option value="2">Semua</option>
                                </select>
                            </div>
                        </div> --}}
                    <div id="overtime-chart" class="m-t-5 m-b-5">
                        
                    </div>
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
<!-- Selectize -->
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- Loadingoverlay -->
<script src={{ url("/plugins/loadingoverlay/loadingoverlay.min.js") }}></script>
@endpush

@push('custom-scripts')
@include('scripts._defer-ajax-dt-script')
@include('scripts._save-tab-state-script')
@include('scripts.dashboards.employee._leave')
@include('scripts.dashboards.employee._overtime')
@include('scripts.dashboards.employee._time_event')
@include('scripts.dashboards.employee._permit')
@include('scripts.dashboards.employee._main')
@endpush

@push('on-ready-scripts')
TabStatePlugins.init();
@endpush