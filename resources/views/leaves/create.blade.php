@extends('layouts.app') 

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Cuti'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Mengajukan Cuti</h4>
      </div>
      @include('layouts._flash')
      <div class="alert alert-success fade in">
          <i class="fa fa-calendar pull-left"></i>
          <p>Silahkan pilih tanggal mulai cuti dengan memilih kalender di sebelah kiri.</p>
          <br />
          <i class="fa fa-calendar pull-left"></i>
          <p>Silahkan pilih tanggal berakhir cuti dengan memilih kalendear di sebelah kanan.</p>
          <br />
          <i class="fa fa-paper-plane pull-left"></i>
          <p>Pastikan bahwa tanggal yang dipilih tidak terdapat hari libur kerja/nasional di dalam jadwal kerja Anda.</p>
          <br />
          <i class="fa fa-paper-plane pull-left"></i>
          <p>Jika tanggal pengajuan terdapat hari libur, silahkan bagi dalam formulir terpisah agar tidak terdapat hari libur dalam pengajuan.
	      Contoh: Pengajuan tanggal 16/08 sampai dengan 18/08 dimana tanggal 17/08 adalah hari libur, maka pengajuan di ajukan 2 (dua) kali yaitu tanggal 16/08 s/d 16/08 dan pengajuan tanggal 18/08 s/d 18/08.
      	  </p>          
      </div>
      <div class="panel-body">
        {!! Form::open([
            'url' => route('leaves.store'), 
            'method' => 'post', 
            'class'=>'form-horizontal', 
            'data-parsley-validate' => 'true'
            ])
        !!}
        @include('leaves._form')
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
  'end_date'   => config('emss.modules.leaves.end_date') 
])
@include('scripts._structdisp-select-script')
@endpush 
@push('on-ready-scripts') 
DaterangePickerPlugins.init();
StructdispSelectPlugins.init();
@endpush