@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Slip Gaji'])
<div class="panel panel-prussian">
        <div class="panel-heading">
          <h4 class="panel-title">Slip Gaji</h4>
          
        </div>
        <div class="alert alert-success fade in">
          <i class="fa fa-key pull-left"></i>
          <p>Untuk membuka slip gaji gunakan kata kunci &nbsp;<b>XXXDDMMYYYY</b> (Kombinasi tiga digit terakhir NIK+tanggal lahir).</p>
          <i class="fa fa-key pull-left"></i>
          <p>Contoh NIK 1699 dan tanggal lahir 06 Desember 1996, password slip gaji <b>69906121996</b></p>             
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
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<script src={{ url('plugins/select2/js/select2.full.min.js') }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}


@endpush
@push('custom-scripts')
@include('scripts._select-type')
@endpush

@push('on-ready-scripts') 
FilterPlugins.init();
@endpush