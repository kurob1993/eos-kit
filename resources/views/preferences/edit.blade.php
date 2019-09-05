@extends('layouts.app') 
@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Preference'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Mengisi Preference</h4>
      </div>
      @include('layouts._flash')
      <div class="alert alert-success fade in">
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Pastikan bahwa start date and end date telah dipilih.</p>
        <br />
        <br />
      </div>
      <div class="panel-body">
        {!! Form::open([ 'url' => route('preference.update', $preferdis->id), 'method' => 'post', 
        'class'=>'form-horizontal', 'data-parsley-validate'=> 'true', 
        'files' => 'true' ]) !!}
         {{ method_field('PUT') }}

          @include('preferences._form') 
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
<!-- Pace -->
<script src={{ url( "/plugins/pace/pace.min.js") }}></script>
@endpush 

@push('plugin-scripts')
<script src={{ url( "/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<script src={{ url( "/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url( "/plugins/selectize/selectize.min.js") }}></script>
<script src={{ url( "/plugins/parsley/dist/parsley.js") }}></script>
@endpush 

@push('custom-scripts')
@include('scripts._daterange-picker-script', [
  'start_date' => config('emss.modules.permits.start_date'),
  'end_date'   => config('emss.modules.permits.end_date') 
])
@include('scripts._preference-script')
@endpush 
@push('on-ready-scripts') 
DaterangePickerPlugins.init();
PreferencePlugins.init();
@endpush