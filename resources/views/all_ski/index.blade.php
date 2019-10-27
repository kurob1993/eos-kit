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
                    <label for="month">Month:</label>
                    <select id="month" name="month" class="form-control" required>
                        <option selected value=""> .:: All Month ::. </option>
                        @foreach ($data['monthList'] as $key => $item)
                        <option value="{{ $key }}"> {{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="year">Year:</label>
                    <select id="year" name="year" class="form-control" required>
                        <option selected value=""> .:: All Year ::. </option>
                        @foreach ($data['yearList'] as $item)
                        <option value="{{ $item->year }}"> {{ $item->year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                        <label for="stage">Stage:</label>
                        <select id="stage" name="stage" class="form-control" required>
                            <option selected value=""> .:: All Stage ::. </option>
                            @foreach ($data['stage'] as $item)
                            <option value="{{ $item->id }}"> {{ $item->description }}</option>
                            @endforeach
                        </select>
                </div>
                <div class="col-sm-4">
                        <label for="search">Search:</label>
                        <input type="text" name="" id="text" class="form-control">
                    </div>
                <div class="col-sm-2 m-t-25">
                    <input type="submit" name="" id="search" value="search" class="btn btn-md btn-primary" required>
                    <form action="{{route('personnel_service.ski.download')}}" 
                        method="post" style="display: inline">
                        {{csrf_field()}}
                        <input type="hidden" name="year" id="excel_year">
                        <input type="hidden" name="month" id="excel_month">
                        <input type="hidden" name="stage" id="excel_stage">
                        <input type="hidden" name="text" id="excel_text">
                        <button class="btn btn-warning" id="excel">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                            Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            {!! $html->table(['class'=>'table table-striped', 'width'=>'100%']) !!}
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
</script>
@endpush