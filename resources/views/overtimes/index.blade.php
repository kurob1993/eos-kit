@extends('layouts.app') 

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Lembur'])
<div class="row">
    <div class="col-lg-12 col-xl-9">
      <div class="panel panel-prussian">
        <div class="panel-heading">
          <h4 class="panel-title">Daftar Lembur Saya</h4>
        </div>
        @include('layouts._flash')
        <div class="panel-body">
          <p> <a class="btn btn-primary" href="{{ route('overtimes.create') }}">Tambah</a> </p>
          <div class="table-responsive">
            {!! $html->table(['class'=>'table table-striped']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endcomponent
<!-- end page container -->
@endsection


@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/data-table.css") }} rel="stylesheet" />
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush