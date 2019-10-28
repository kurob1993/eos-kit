@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Personnel Service Dashboard'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="panel-title" style="display: inline-block">{{$data['title']}}</h4>
            </div>
        </div>
    </div>
    @include('layouts._flash')
    <div class="panel-body">
        <div class="row" style="margin-bottom: 20px">
            <div>
                <div class="col-sm-2">
                    <label for="month">Bulan:</label>
                    <select id="month" name="month" class="form-control" required>
                        <option selected value=""> .:: Semua Bulan ::. </option>
                        @foreach ($data['monthList'] as $key => $item)
                        <option value="{{ $key }}"> {{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="year">Tahun:</label>
                    <select id="year" name="year" class="form-control" required>
                        <option selected value=""> .:: Semua Tahun ::. </option>
                        @foreach ($data['yearList'] as $item)
                        <option value="{{ $item->year }}"> {{ $item->year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="stage">Tahapan:</label>
                    <select id="stage" name="stage" class="form-control" required>
                        <option selected value=""> .:: Semua Tahapan ::. </option>
                        @foreach ($data['stage'] as $item)
                        <option value="{{ $item->id }}"> {{ $item->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <label for="search">
                        Nik: <i>(cari lebih dari satu nik pisah dengan koma)</i>
                    </label>
                    <input type="text" name="" id="text" class="form-control">
                </div>
                <div class="col-sm-2 m-t-25">
                    <button id="search" value="search" class="btn btn-sm btn-primary">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        Cari
                    </button>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        Unduh
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            {!! $html->table(['class'=>'table table-striped', 'width'=>'100%']) !!}
        </div>
    </div>

</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Unduh Data</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('personnel_service.ski.download')}}" method="post" style="display: inline">
                    {{csrf_field()}}
                    <input type="hidden" name="year" id="excel_year">
                    <input type="hidden" name="month" id="excel_month">
                    <input type="hidden" name="stage" id="excel_stage">
                    <input type="hidden" name="text" id="excel_text">
                    <input type="hidden" name="type" id="type" value="all">
                    <button class="btn btn-warning" data-toggle="modal" type="submit" id="excel">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        Semua Kolom
                    </button>
                </form>
                <form action="{{route('personnel_service.ski.download')}}" method="post" style="display: inline">
                    {{csrf_field()}}
                    <input type="hidden" name="year" id="excel_year2">
                    <input type="hidden" name="month" id="excel_month2">
                    <input type="hidden" name="stage" id="excel_stage2">
                    <input type="hidden" name="text" id="excel_text2">
                    <input type="hidden" name="type" id="type" value="rekap">
                    <button class="btn btn-primary" data-toggle="modal" type="submit" id="excel2">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        Rekap
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
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
<style>
    .blink {
        animation: blink-animation 1s steps(5, start) infinite;
        -webkit-animation: blink-animation 1s steps(5, start) infinite;
    }

    @keyframes blink-animation {
        to {
            visibility: hidden;
        }
    }

    @-webkit-keyframes blink-animation {
        to {
            visibility: hidden;
        }
    }
</style>
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}

<script>
    oTable = $('.table').DataTable();
    $('#search').click(function(){
        var month = $('#month').val();
        var year = $('#year').val();
        var stage = $('#stage').val();
        var text = $('#text').val();
        var cari = month+'|'+year+'|'+text+'|'+stage;
        oTable.search(cari).draw();
    })
    $('#excel').click(function(){
        var month = $('#month').val();
        var year = $('#year').val();
        var stage = $('#stage').val();
        var text = $('#text').val();
        $('#excel_year').val(year);
        $('#excel_month').val(month);
        $('#excel_stage').val(stage);
        $('#excel_text').val(text);
    })
    $('#excel2').click(function(){
        var month = $('#month').val();
        var year = $('#year').val();
        var stage = $('#stage').val();
        var text = $('#text').val();
        $('#excel_year2').val(year);
        $('#excel_month2').val(month);
        $('#excel_stage2').val(stage);
        $('#excel_text2').val(text);
    })
</script>
@endpush