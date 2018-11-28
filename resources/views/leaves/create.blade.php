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
<script>
  (handleDateRangePicker = function() {
  $("#datepicker-range").datepicker({
    inputs: $("#datepicker-range-start, #datepicker-range-end"),
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    startDate: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
    endDate: new Date(new Date().getFullYear(), new Date().getMonth() + 4, 5),
    // datesDisabled: ['2018-09-01'],
  });

  var start = $("#datepicker-range-start");
  var end = $("#datepicker-range-end");

  function days_diff(s, e) {
    var diff = new Date(e - s);
    var days = diff / 1000 / 60 / 60 / 24 + 1;
    return isNaN(days) ? 1 : days;
  }

  function today(number) {
    var today = new Date();
    var dd = today.getDate() + number;
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
      dd = "0" + dd;
    }
    if (mm < 10) {
      mm = "0" + mm;
    }
    return yyyy + "-" + mm + "-" + dd;
  }

  start.datepicker("update", today(0)),
    end.datepicker("update", today(1)),
    start.on("changeDate", function() {
      $("#start_date").val($(this).datepicker("getFormattedDate"));
      $("#deduction").val(
        days_diff(start.datepicker("getUTCDate"), end.datepicker("getUTCDate"))
      );
    }),
    end.on("changeDate", function() {
      $("#end_date").val($(this).datepicker("getFormattedDate"));
      $("#deduction").val(
        days_diff(start.datepicker("getUTCDate"), end.datepicker("getUTCDate"))
      );
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
  url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/minSuperintendentBoss',
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
  
   @if (Auth::user()->employee()->first()->canDelegate())
  
  var subOptions = {
    persist: false,
    valueField: "name",
    labelField: "personnel_no",
    searchField: ["personnel_no", "name"],
    options: [    ],
    render: {
      item: function(item, escape) {
        return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
      },
      option: function(item, escape) {
        var label = item.personnel_no || item.name;
        var caption = item.personnel_no ? item.name : null;
        return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
      }
    }
  };

  $.ajax({
  url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/subordinates',
      type: 'GET',
      dataType: 'json',
      error: function() {},
      success: function(res) {
        var newOptions = [];
        for (var key in res) {
          var o = {name: res[key].name, personnel_no: res[key].personnel_no};
          newOptions.push(o);
        }
        subOptions.options = newOptions;
        var subSelect = $(".sub-selectize").selectize(subOptions);
        var selectize = subSelect[0].selectize;
    }
  });
  
  @endif

}),

(AbsencePlugins = (function() {
  "use strict";
  return {
    init: function() {
      handleDateRangePicker(), handleSelectpicker();
    }
  };
})());

</script>

@endpush @push('on-ready-scripts') AbsencePlugins.init(); 
@endpush