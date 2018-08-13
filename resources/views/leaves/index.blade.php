@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Cuti'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Daftar Cuti Saya</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    <p> <a class="btn btn-primary" href="{{ route('leaves.create') }}">Tambah</a> </p>
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
    <link href="/plugins/DataTables/css/data-table.css" rel="stylesheet" />
    <!-- Selectize -->
    <link href="/plugins/selectize/selectize.css" rel="stylesheet">
    <link href="/plugins/selectize/selectize.bootstrap3.css" rel="stylesheet">
    <!-- Pace -->    
    <script src="/plugins/pace/pace.min.js"></script>
@endpush

@push('plugin-scripts')
<!-- Selectize -->
<script src="/plugins/selectize/selectize.min.js"></script>
<!-- DataTables -->
<script src="/plugins/DataTables/js/jquery.dataTables.js"></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush