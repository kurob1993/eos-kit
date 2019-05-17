@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Personnel Service Dashboard'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="panel-title" style="display: inline-block">Send To SAP (Absence)</h4>
            </div>
            <div class="col-sm-6" style="display: inline-block">
                <div class="pull-right">
                    switch to : 
                    <button class="btn btn-danger btn-xs form-inline">Attendance</button>
                </div>
            </div>
        </div>
    </div>
    @include('layouts._flash')
    <div class="panel-body">
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
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush