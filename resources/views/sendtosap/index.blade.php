@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Personnel Service Dashboard'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="panel-title" style="display: inline-block">Send To SAP ({{$data['title']}})</h4>
            </div>
            <div class="col-sm-6" style="display: inline-block">
                <div class="pull-right">
                    <span style="margin-right : 15px">
                        Switch to :
                        <a class="btn btn-danger btn-xs form-inline"
                            href="{{ route( $data['switch'] ) }}">{{ $data['button']}}</a>
                    </span>
                    |
                    <span style="margin-left : 15px">
                        <button class="btn btn-primary btn-xs form-inline" data-toggle="modal" data-target="#myModal">
                            <span class="blink"> Download data {{ $data['title']}} </span>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    @include('layouts._flash')
    <div class="panel-body">
        <div class="row" style="margin-bottom: 20px">
            <div>
                <div class="col-sm-3">
                    <label for="month">Month:</label>
                    <select id="month" name="month" class="form-control" required>
                        <option selected value=""> .:: All Month ::. </option>
                        @foreach ($data['monthList'] as $item)
                        <option value="{{ $item->month }}"> {{ $item->month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="year">Year:</label>
                    <select id="year" name="year" class="form-control" required>
                        <option selected value=""> .:: All Year ::. </option>
                        @foreach ($data['yearList'] as $item)
                        <option value="{{ $item->year }}"> {{ $item->year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <label for="search">Search:</label>
                    <input type="text" name="" id="text" class="form-control">
                </div>
                <div class="col-sm-2 m-t-25">
                    <input type="submit" name="" id="search" value="search" class="btn btn-md btn-primary" required>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            {!! $html->table(['class'=>'table table-striped', 'width'=>'100%']) !!}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Download</h4>
                </div>
                <form method="post" action="{{ route( $data['download'] ) }}" class="form-horizontal">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Month:</label>
                            <div class="col-sm-10">
                                <select name="year" class="form-control" required>
                                    <option selected value=""> .:: Pilih Tahun ::. </option>
                                    @foreach ($data['yearList'] as $item)
                                    <option value="{{ $item->year }}"> {{ $item->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Year:</label>
                            <div class="col-sm-10">
                                <select name="month" class="form-control" required>
                                    <option selected value=""> .:: Pilih Bulan ::. </option>
                                    @foreach ($data['monthList'] as $item)
                                    <option value="{{ $item->month }}"> {{ $item->month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Download</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
        var text = $('#text').val();
        var cari = month+'|'+year+'|'+text;
        oTable.search(cari).draw();
    })
</script>
@endpush