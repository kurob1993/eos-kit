@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Perjalanan Dinas'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Mengajukan Perjalanan Dinas</h4>
      </div>
      @include('layouts._flash')
      <div class="alert alert-success fade in">
        <i class="fa fa-calendar pull-left"></i>
        <p>Mohon diisi dengan teliti tanggal mulai dan berakhir perjalanan dinas</p>
        <br />
        <i class="fa fa-warning pull-left"></i>
        <p>Lampiran perjalanan dinas harus diisi dengan tanda tangan basah, File lampiran berformat jpeg, png, dan pdf dengan maksimal ukuran 500kb</p>
      </div>
      <div class="panel-body">
        {!! Form::open([
        'url' => route('travels.store'),
        'method' => 'post',
        'enctype' => 'multipart/form-data',
        'class'=>'form-horizontal',
        'data-parsley-validate' => 'true'
        ])
        !!}
        @include('travels._form')
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endcomponent
<!-- end page container -->

@endsection

@push('styles')
<link href={{ url("/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-select/bootstrap-select.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
<link href={{ url("/plugins/parsley/src/parsley.css") }} rel="stylesheet" />
<!-- Pace -->
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<script src={{ url("/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<script src={{ url("/plugins/parsley/dist/parsley.js") }}></script>
@endpush

@push('custom-scripts')
@include('scripts._daterange-picker-script',[
'start_date' => config('emss.modules.leaves.start_date'),
'end_date' => config('emss.modules.leaves.end_date')
])
@endpush
@push('on-ready-scripts')
DaterangePickerPlugins.init();
@include('travels._script')
SelectTravel.init();
@endpush