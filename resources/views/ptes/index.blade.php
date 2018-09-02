@extends('layouts.app') 
@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Izin & Tidak Slash'])
<div class="row">
    <div class="col-lg-12 col-xl-9">
        <!-- begin of ptes nav-tabs  -->
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab-ptes" data-toggle="tab" aria-expanded="true">
                    Izin
                </a>
            </li>
            <li class="">
                <a href="#tab-time-events" data-toggle="tab" aria-expanded="true">
                    Tidak Slash
                </a>
            </li>
        </ul>
        <!-- end of ptes nav-tabs  -->

        <!-- begin of ptes tab-content  -->
        <div class="tab-content">
            <!-- begin of ptes first tab  -->
            <div class="tab-pane fade active in" id="tab-ptes">
                <div class="panel-body">
                        <p> 
                            <a class="btn btn-primary" href="{{ route('permits.create') }}">
                                Tambah
                            </a> 
                        </p>
                        <div class="table-responsive">
                            {!! $html->table(['class'=>'table table-striped']) !!}
                        </div>
                </div>
            </div>
            <!-- end of ptes first tab  -->

            <!-- begin of ptes second tab  -->
            <div class="tab-pane fade" id="tab-time-events">
                <div class="panel-body">
                </div>
            </div>
            <!-- end of ptes second tab  -->

        </div>
        <!-- begin of ptes tab-content  -->
    </div>

</div>
@endcomponent
<!-- end page container -->
@endsection
 @push('styles')
<!-- DataTables -->
<link href={{ url( "/plugins/DataTables/css/data-table.css") }} rel="stylesheet" />
<!-- Selectize -->
<link href={{ url( "/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url( "/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
<!-- Pace -->
<script src={{ url( "/plugins/pace/pace.min.js") }}></script>

@endpush @push('plugin-scripts')
<!-- Selectize -->
<script src={{ url( "/plugins/selectize/selectize.min.js") }}></script>
<!-- DataTables -->
<script src={{ url( "/plugins/DataTables/js/jquery.dataTables.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!} 
@endpush