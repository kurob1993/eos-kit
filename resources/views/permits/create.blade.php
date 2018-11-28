@extends('layouts.app') 
@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Izin'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Mengajukan Izin</h4>
      </div>
      @include('layouts._flash')
      <div class="alert alert-success fade in">
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Pastikan bahwa tanggal yang dipilih tidak terdapat hari libur kerja/nasional di dalam jadwal kerja Anda.</p>
        <br />
        <i class="fa fa-calendar pull-left"></i>
        <p>Silahkan pilih tanggal mulai & berakhir izin dengan memilih kalender.</p>
        <br />
        <i class="fa fa-sliders pull-left"></i>
        <p>Silahkan isi jenis izin yang akan Anda ajukan.</p>
        <br />
        <i class="fa fa-upload pull-left"></i>
        <p>Silahkan unggah berkas lampiran yang bersesuaian dengan izin Anda (JPG/PNG/PDF maks. 500Kb).</p>
        <br />
        <i class="fa fa-book pull-left"></i>
        <p>Silahkan isi keterangan izin Anda.</p>
        <br />
      </div>
      <div class="panel-body">
        {!! Form::open([ 'url' => route('permits.store'), 'method' => 'post', 
        'class'=>'form-horizontal', 'data-parsley-validate'=> 'true', 
        'files' => 'true' ]) !!}
        @include('permits._form') {!! Form::close() !!}
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
@include('scripts._structdisp-select-script')
@endpush 
@push('on-ready-scripts') 
DaterangePickerPlugins.init();
StructdispSelectPlugins.init();
@endpush