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
                    <div id="leave-chart" class="m-t-5 m-b-5">Fusionchart for leaves will be rendered here.</div>
                </div>
            </div>
            <!-- end of leaves tab  -->

            <!-- begin of permits tab  -->
            <div class="tab-pane fade" id="tab-permits">
                <div class="panel-body p-0">
                    <div id="permit-chart" class="m-t-5 m-b-5">Fusionchart for permits will be rendered here.</div>
                </div>
            </div>
            <!-- end of permits tab  -->

            <!-- begin of time-events tab  -->
            <div class="tab-pane fade" id="tab-time-events">
                <div class="panel-body p-0">
                    <div id="time-event-chart" class="m-t-5 m-b-5">Fusionchart for time events will be rendered here.
                    </div>
                </div>
            </div>
            <!-- end of time-events tab  -->

            <!-- begin of overtimes tab  -->
            <div class="tab-pane fade" id="tab-overtimes">
                <div class="panel-body p-0">
                    <form method="post" id="form-overtime" action="{{ route('dashboard.employee.filter') }}">
                        {{ csrf_field() }}
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="filter-boss">Atasan</label>
                                <select name="ofboss" id="filter-boss" 
                                    class="form-control" onchange="this.form.submit()">
                                    @foreach ($subordinatesBoss as $boss)
                                    <option value="{{ $boss->personnel_no }}"
                                        @if ($ofboss->personnel_no == $boss->personnel_no) selected="" @endif >
                                        {{ $boss->name }} - {{ $boss->org_unit_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter-overtime-month">Bulan</label>
                                <select name="ofmonth" id="filter-overtime-month" 
                                    class="form-control" onchange="this.form.submit()">
                                    @foreach ($oFMonths as $y)
                                    <option value="{{ $y->month }}" 
                                        @if ($ofmonth == $y->month) selected="" @endif>
                                        {{ $y->month }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                                                
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter-overtime-year">Tahun</label>
                                <select name="ofyear" id="filter-overtime-year" 
                                    class="form-control" onchange="this.form.submit()">
                                    @foreach ($oFYears as $y)
                                    <option value="{{ $y->year }}" 
                                        @if ($ofyear == $y->year) selected="" @endif>
                                        {{ $y->year }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="checkbox">
                                        <label></label>
                                        <label>
                                            <input type="checkbox" value="">
                                            0 Jam
                                        </label>
                                    </div>
                            </div>
                        </div>
                    </form>
                    <div id="overtime-chart" class="m-t-5 m-b-5">Fusionchart for overtimes will be rendered here.</div>
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
{{-- {!! $leaveChart->render() !!}
{!! $permitChart->render() !!}
{!! $timeEventChart->render() !!} --}}
{!! $overtimeChart->render() !!}
<!-- Selectize -->
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
@endpush

@push('custom-scripts')
@include('scripts._defer-ajax-dt-script')
@include('scripts._save-tab-state-script')
@endpush

@push('on-ready-scripts')
TabStatePlugins.init();
@endpush