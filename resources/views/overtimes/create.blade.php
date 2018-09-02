@extends('layouts.app') 

@section('content')

<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Pengajuan Lembur'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Mengajukan Lembur</h4>
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
      </div>
      <div class="panel-body">
        {!! Form::open([
            'url' => route('overtimes.store'), 
            'method' => 'post', 
            'class'=>'form-horizontal', 
            'data-parsley-validate' => 'true'
            ])
        !!}
        @include('overtimes._form')
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endcomponent
<!-- end page container -->

@endsection

@push('styles')
<link href={{ url("/plugins/bootstrap-select/bootstrap-select.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet"> 
<link href={{ url("/plugins/parsley/src/parsley.css") }} rel="stylesheet" />
<!-- jquery-ui-multidatepicker -->
<link href={{ url("/plugins/Multiple-Dates-Picker/jquery-ui.multidatespicker.css") }} rel="stylesheet" />
<link href={{ url("/plugins/Multiple-Dates-Picker/jquery-ui-1.10.0.custom.css") }} rel="stylesheet" />
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<script src={{ url("/plugins/parsley/dist/parsley.js") }}></script>
<!-- jquery-ui-multidatepicker -->
<script src={{ url("/plugins/Multiple-Dates-Picker/jquery-ui.multidatespicker.js") }}></script>
@endpush 

@push('custom-scripts')
<script>
(handleMultiDatePicker = function() {
  $('#mdp-demo').multiDatesPicker({
    altField: '#altField'
  });  
}),
(handleSelectpicker = function() {
  var bossOptions = {
    persist: false,
    valueField: "personnel_no",
    labelField: "name",
    searchField: ["name", "personnel_no"],
    options: [ ],
    render: {
      item: function(item, escape) {
        return (
          "<div>" +
          (item.personnel_no
            ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;"
            : "") +
          (item.name
            ? '<span class="name">' + escape(item.name) + "</span>"
            : "") +
          "</div>"
        );
      },
      option: function(item, escape) {
        var label = item.personnel_no || item.name;
        var caption = item.personnel_no ? item.name : null;
        return (
          "<div>" +
          '<span class="label label-default">' +
          escape(label) +
          "</span>&nbsp;" +
          (caption
            ? '<span class="caption">' + escape(caption) + "</span>"
            : "") +
          "</div>"
        );
      }
    },
  };

  $.ajax({
  url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/closestBoss',
      type: 'GET',
      dataType: 'json',
      error: function() {},
      success: function(res) {
        var newOptions = [];
        var o = {name: res.name, personnel_no: res.personnel_no};
        newOptions.push(o);
        bossOptions.options = newOptions;
        var bossSelect = $(".boss-selectize").selectize(bossOptions);
        var selectize = bossSelect[0].selectize;
        selectize.setValue(res.personnel_no, false);
    }
  });  
}),

(AttendanceQuotaPlugins = (function() {
  "use strict";
  return {
    init: function() {
      handleMultiDatePicker(), handleSelectpicker();
    }
  };
})());

</script>

@endpush 
@push('on-ready-scripts') 
AttendanceQuotaPlugins.init(); 
@endpush