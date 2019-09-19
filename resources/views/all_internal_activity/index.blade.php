@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Internal Activity'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <h4 class="panel-title">Internal Activity</h4>
    </div>
    @include('layouts._flash')
    <div class="panel-body">

        <div class="row m-b-15">
            <div class="col-sm-3">
                <label for="month">Month:</label>
                <select id="month" name="month" class="form-control" required>
                    @foreach ($data['monthList'] as $item)
                    <option value="{{ $item->month }}"> {{ $item->month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="year">Year:</label>
                <select id="year" name="year" class="form-control" required>
                    @foreach ($data['yearList'] as $item)
                    <option value="{{ $item->year }}"> {{ $item->year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label for="year">Stage:</label>
                <select id="year" name="year" class="form-control" required>
                    <option value="">All</option>
                    @foreach ($stage as $item)
                    <option value="{{ $item->id }}"> {{ $item->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 m-t-25">
                <button class="btn btn-info">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    Excel
                </button>
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
    var nik = '{{ Auth::user()->personnel_no }}';
$('#data').select2({
    theme: "bootstrap",
    ajax: {
        url: "{{ url('activity/list') }}/"+nik,
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

oTable = $('.table').DataTable();
$('#search').click(function(){
    var month = $('#month').val();
    var year = $('#year').val();
    var cari = month+'|'+year;
    oTable.search(cari).draw();
})
</script>
@endpush