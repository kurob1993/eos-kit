@extends('layouts.app') 

@section('content')

<!-- begin #page-container -->
@component($pageContainer, ['page_header' => 'Sasaran Kerja Karyawan'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Input Sasaran Kerja Karyawan</h4>
      </div>
      <div class="panel-body">
        {!! Form::open([
            'url' => $formRoute, 
            'method' => 'post', 
            'class'=>'form-horizontal', 
            'data-parsley-validate' => 'true'
            ])
        !!}
        @include('ski._formas')
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endcomponent
<!-- end page container -->

@endsection

@push('styles')
<!-- Datepicker -->
<link href={{ url("/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />
<!-- Selectize -->    
<link href={{ url("/plugins/bootstrap-select/bootstrap-select.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet"> 
<!-- Parsley -->    
<link href={{ url("/plugins/parsley/src/parsley.css") }} rel="stylesheet" />
<!-- Timepicker -->    
<link href={{ url( "/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css") }} rel="stylesheet" />
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<!-- Selectize -->    
<script src={{ url("/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- Parsley --> 
<script src={{ url("/plugins/parsley/dist/parsley.js") }}></script>
<!-- Timepicker --> 
<script src={{ url( "/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js") }}></script>
@endpush 

@push('custom-scripts')
@include('scripts._ski-script',[
  'start_date' =>  config('emss.modules.time_events.start_date'),
  'end_date' =>  config('emss.modules.time_events.end_date')
])
<script>
  function setNilai(id) {
    var bobot = $('#bobot'+id).val();
    var skor = $('#skor'+id).val();
    $('#nilai'+id).val(bobot*skor);
  }
</script>
@endpush

@push('on-ready-scripts') 
SecretaryOvertimePlugins.init(); 
@endpush