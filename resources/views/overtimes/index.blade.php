@extends('layouts.app') 

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Lembur'])
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
<div class="modal fade" id="modal-dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Lembur (ID: <span id="title-span"></span>)</h4>
      </div>
      <div class="modal-body">
        
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
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush

@push('custom-scripts')
@include('layouts._modal-detail-script')
@endpush

@push('on-ready-scripts') 
ModalDetailPlugins.init();
@endpush