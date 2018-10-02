@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Bantuan'])
<div class="row">
  <!-- begin col-3 -->
  <div class="col-xs-12">
    <div class="widget widget-stats bg-green">
      <div class="stats-icon"><i class="fa fa-phone-square"></i></div>
      <div class="stats-info">
        <h4>Ada pertanyaan seputar penggunaan?</h4>
        <p>Hubungi 72405, 72193, 72163</p>	
      </div>
    </div>
  </div>
  <!-- end col-3 -->

</div>
<div class="player-container">
  <video
    id="video"
    class="video-js vjs-fluid"
    controls data-setup={}>
    <source src="storage/pendahuluan.mp4" type="video/mp4">
  </video>
  <video
    id="video2"
    class="video-js vjs-fluid"
    controls data-setup={}>
    <source src="storage/cuti.mp4" type="video/mp4">
  </video>
  <video
    id="video3"
    class="video-js vjs-fluid"
    controls data-setup={}>
    <source src="storage/izin.mp4" type="video/mp4">
  </video>
  <video
    id="video4"
    class="video-js vjs-fluid"
    controls data-setup={}>
    <source src="storage/lembur.mp4" type="video/mp4">
  </video>
  <video
    id="video5"
    class="video-js vjs-fluid"
    controls data-setup={}>
    <source src="storage/tidak_slash.mp4" type="video/mp4">
  </video>
  <br />
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- VideoJS -->    
<link href={{ url("/plugins/videojs/video-js.min.css") }} rel="stylesheet">
<link href={{ url("/plugins/videojs/videojs-playlist-ui.css") }} rel="stylesheet">
<link href={{ url("/plugins/bootstrap-wizard/css/bwizard.min.css") }} rel="stylesheet">
{{-- <script src={{ url("/plugins/videojs/videojs-ie8.min.js") }}></script> --}}
@endpush

@push('plugin-scripts')
<!-- VideoJS -->
<script src={{ url("/plugins/videojs/video.min.js") }}></script>
<script src={{ url("/plugins/videojs/videojs-playlist.min.js") }}></script>
<script src={{ url("/plugins/videojs/videojs-playlist-ui.min.js") }}></script>
<script src={{ url("plugins/bootstrap-wizard/js/bwizard.js") }}></script>
<script src={{ url("plugins/bootstrap-wizard/form-wizards.demo.min.js") }}></script>
@endpush

@push('plugin-scripts')

@endpush

@push('on-ready-scripts') 
FormWizard.init();
@endpush