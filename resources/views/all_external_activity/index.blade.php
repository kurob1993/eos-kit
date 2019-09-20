@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'External Activity'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <h4 class="panel-title">External Activity</h4>
    </div>
    @include('layouts._flash')
    <div class="panel-body">

        <div class="row m-b-15">
            <div class="col-sm-3">
                <label for="month">Month:</label>
                <select id="month" name="month" class="form-control" required onchange="month(this.value)">
                    <option value="">.: Pilih Bulan :.</option>
                    @foreach ($data['monthList'] as $item)
                        @php
                            $month_num = $item->month;
                            $month_name = date("F", mktime(0, 0, 0, $month_num, 10));
                        @endphp
                    <option value="{{ $item->month }}"> {{ $month_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="year">Year:</label>
                <select id="year" name="year" class="form-control" required onchange="year(this.value)">
                    <option value="">.: Pilih Tahun :.</option>
                    @foreach ($data['yearList'] as $item)
                    <option value="{{ $item->year }}"> {{ $item->year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="year">Stage:</label>
                <select id="stage" name="stage" class="form-control" required onchange="stage(this.value)">
                    <option value="">All</option>
                    @foreach ($stage as $item)
                    <option value="{{ $item->id }}"> {{ $item->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 m-t-25">
                <form action="{{route('personnel_service.external-activity.export')}}" method="get">
                    <input type="hidden" id="export_month" name="month" value="">
                    <input type="hidden" id="export_year" name="year" value="">
                    <input type="hidden" id="export_stage" name="stage" value="">
                    <button type="submit" class="btn btn-info">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        Excel
                    </button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive ">
                {!! $html->table(['class'=>'table table-striped', 'width'=>'100%']) !!}
            </div>
        </div>
    </div>
</div>
@endcomponent

<!-- end page container -->
@endsection

@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/dataTables.bootstrap.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Responsive/css/responsive.dataTables.min.css") }} rel="stylesheet" />
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/js/dataTables.bootstrap.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
<script>
    function fillter() {
        var month = localStorage.getItem("ActivitySelectMonth");
        var year = localStorage.getItem("ActivitySelectYear");
        var stage = localStorage.getItem("ActivitySelectStage");
        var cari = month+'|'+year+'|'+stage;
        
        oTable = $('.table').DataTable();
        oTable.search(cari).draw();

        $('#export_month').val(month);
        $('#export_year').val(year);
        $('#export_stage').val(stage);
    }

   function month(params) {
      localStorage.setItem("ActivitySelectMonth", params);
      this.fillter();
   }

   function year(params) {
      localStorage.setItem("ActivitySelectYear", params);
      this.fillter();
   }

   function stage(params) {
      localStorage.setItem("ActivitySelectStage", params);
      this.fillter();
   }
</script>
@endpush

@push('on-ready-scripts')
@include('all_external_activity.script')
@endpush