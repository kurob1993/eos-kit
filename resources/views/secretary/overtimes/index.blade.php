@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.secretary._page-container', ['page_header' => 'Lembur Karyawan'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <h4 class="panel-title">Pengajuan Lembur Karyawan</h4>
    </div>
  @include('layouts._flash')
  <div class="panel-body">
      <p> <a class="btn btn-primary" href="{{ route('secretary.overtimes.create') }}">Tambah</a> </p>
    <div class="table-responsive">
      {!! $dataTable->table(['class'=>'table table-striped display nowrap', 'width' => '100%']) !!}
    </div>
  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/dataTables.bootstrap.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Responsive/css/responsive.dataTables.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Buttons/css/buttons.dataTables.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Buttons/css/buttons.bootstrap.min.css") }} rel="stylesheet" />
  <!-- Pace -->    
  <script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/js/dataTables.bootstrap.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Buttons/js/dataTables.buttons.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Buttons/js/buttons.bootstrap.min.js") }}></script>
<script src={{ url("/vendor/datatables/buttons.server-side.js") }}></script>
@endpush

@push('custom-scripts')
<!-- Generated scripts from DataTables -->
{!! $dataTable->scripts() !!}
@endpush

@push('on-ready-scripts')
@endpush