@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Preferences Dashboard'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="panel-title" style="display: inline-block">Prefences Data </h4>
            </div>
        </div>
    </div>
    @include('layouts._flash')
    <div class="panel-body">
        <div class="row" style="margin-bottom: 20px">
            <div>
                <div class="col-sm-3">
                    <label for="year">Periode:</label>
                    <select id="year" name="year" class="form-control" required>
                        <option selected value=""> .:: All Periode ::. </option>
                        @foreach ($dataperiode as $item)
                            <option value="{{ $item->start_date }} {{ $item->finish_date }}">{{ $item->start_date }} s/d {{ $item->finish_date }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-sm-4">
                    <label for="search">Search:</label>
                    <input type="text" name="" id="text" class="form-control">
                </div> --}}
                <div class="col-sm-4 m-t-25">
                    <input type="submit" name="" id="search" value="search" class="btn btn-md btn-primary" required>
                    <button class="btn btn-primary btn-md form-inline" data-toggle="modal" data-target="#myModal">
                        <span class="blink"> Download <i class="fa fa-file-excel-o"></i> </span>
                    </button>
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
                <form method="post" action="{{ route('pref.download') }}" class="form-horizontal">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Periode:</label>
                            <div class="col-sm-10">
                                <select name="periode" class="form-control" required>
                                    <option selected value=""> .:: Pilih Periode ::. </option>
                                    @foreach ($dataperiode as $item)
                                    <option value="{{ $item->id }}">{{ $item->start_date }} s/d {{ $item->finish_date }}</option>
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
        var year = $('#year').val();
        var text = $('#text').val();
        var cari = year;
        oTable.search(cari).draw();
    })
</script>
@endpush