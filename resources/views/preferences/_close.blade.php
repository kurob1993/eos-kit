@extends('layouts.app') 
@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Preference'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    @include('preferences._tab')
  </div>
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      @include('layouts._flash')
      <div class="alert alert-success fade in">
        <br/>
        <i class="fa fa-paper-plane pull-left"></i>
          <p>Maaf periode pengisian data pada hari ini belum ada.</p>
        <br/>
    </div>
  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<link href={{ url( "/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url( "/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />
<link href={{ url( "/plugins/bootstrap-select/bootstrap-select.min.css") }} rel="stylesheet" />
<link href={{ url( "/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url( "/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
<link href={{ url( "/plugins/parsley/src/parsley.css") }} rel="stylesheet" />
<!-- Pace -->
<script src={{ url( "/plugins/pace/pace.min.js") }}></script>
@endpush 

@push('plugin-scripts')
<script src={{ url( "/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<script src={{ url( "/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url( "/plugins/selectize/selectize.min.js") }}></script>
<script src={{ url( "/plugins/parsley/dist/parsley.js") }}></script>
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->