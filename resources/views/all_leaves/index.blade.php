@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Cuti Karyawan'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Panel Title here</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    <div class="table-responsive">
      {!! $html->table(['class'=>'table table-striped']) !!}
    </div>
  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
    <!-- DataTables -->
    <link href={{ url("/plugins/DataTables/css/data-table.css") }} rel="stylesheet" />
    <!-- Selectize -->
    <link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
    <link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
    <!-- Pace -->    
    <script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- Selectize -->
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush