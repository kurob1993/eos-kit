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
        {{-- <i class="fa fa-paper-plane pull-left"></i>
        <p>Batas maksimal data dislike berjumlah 6. Dengan aturan 3 untuk jabatan level sejajar dan 3 untuk jabatan 1 tingkat diatasnya.</p>
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Pertama gunakan filter jabatan dari level organisasi: direktorat, subdit, divisi, dinas, atau seksi.</p>
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Kedua pilih jabatan yang telah di-filter sesuai level organisasi.</p>
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Ketiga untuk menyimpan data jabatan klik tombol Kirim Data.</p> --}}
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Aturan pengisian preference & dislike:</p>
        <p>Maksimal jabatan yang dapat dipilih masing – masing adalah 6 jabatan dengan ketentuan:</p>
        <ul>
          <li>Pilih 3 jabatan yang sama dengan golongan anda saat ini.</li>
          <li>Pilih 3 jabatan diatas golongan anda saat ini.</li>
          <li>Diwajibkan untuk mengisi preference dan dislike.</li>
        </ul>
        <i class="fa fa-paper-plane pull-left"></i>
        <p>Langkah pengisian:</p>
        <ol>
          <li>Pilih organisasi sesuai urutan unit level organisasi dari paling tinggi ke bawah.</li>
          <li>Pilih jabatan yang diinginkan sesuai aturan pegisian di atas.</li>
          <li>Klik tombol Kirim Data untuk menyimpan data jabatan.</li>
        </ol>
      </div>
      <div class="panel-body">
        {!! Form::open([ 'url' => route('dislike.store'), 'method' => 'post', 
        'class'=>'form-horizontal', 'data-parsley-validate'=> 'true', 
        'files' => 'true' ]) !!}
          @include('preferences._form-ab') 
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
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
@include('scripts._preferdis-script')
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