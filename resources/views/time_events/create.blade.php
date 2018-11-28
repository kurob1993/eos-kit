@extends('layouts.app') 

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Tidak Slash'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Mengajukan Tidak Slash</h4>
      </div>
        <div class="panel-body">
        {!! Form::open([ 
          'url' => route('time_events.store'), 
          'method' => 'post', 
          'class' => 'form-horizontal', 
          'data-parsley-validate' => 'true' 
          ]) 
        !!}
        @include('time_events._form') 
        {!! Form::close() !!}
      </div>
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
<link href={{ url( "/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css") }} rel="stylesheet" />
<!-- Pace -->
<script src={{ url( "/plugins/pace/pace.min.js") }}></script>

@endpush @push('plugin-scripts')
<script src={{ url( "/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<script src={{ url( "/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url( "/plugins/selectize/selectize.min.js") }}></script>
<script src={{ url( "/plugins/parsley/dist/parsley.js") }}></script>
<script src={{ url( "/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js") }}></script>

@endpush @push('custom-scripts')
@include('scripts._structdisp-select-script')
@include('scripts._inline-datepicker-script',[
  'start_date' =>  config('emss.modules.time_events.start_date'),
  'end_date' =>  config('emss.modules.time_events.end_date')
])
@endpush 

@push('on-ready-scripts') 
InlineDatepickerPlugins.init(); 
StructdispSelectPlugins.init();
@endpush