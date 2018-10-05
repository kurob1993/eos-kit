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
<script>
(handleInlineDatePicker) = function() {
  "use strict";
  $("#datepicker-inline").datepicker({ 
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    startDate: '-3d',
    endDate: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 5),
    // datesDisabled: ['2018-09-01'],
    });
  $('#datepicker-inline').on('changeDate', function() {
    $('#start_date').val(
        $('#datepicker-inline').datepicker('getFormattedDate')
    );
  });    
}, 
(handleTimePicker) = function() {
    "use strict";
    $("#from, #to").timepicker({ })
}, 
(handleSelectpicker = function() {
  var minManagerOptions = {
    persist: false,
    valueField: "personnel_no",
    labelField: "name",
    searchField: ["name", "personnel_no"],
    options: [ ],
    render: {
      item: function(item, escape) {
        return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
      },
      option: function(item, escape) {
        var label = item.personnel_no || item.name;
        var caption = item.personnel_no ? item.name : null;
        return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
      }
    },
  };

  var minSuperintendentOptions = {
    persist: false,
    valueField: "personnel_no",
    labelField: "name",
    searchField: ["name", "personnel_no"],
    options: [ ],
    render: {
      item: function(item, escape) {
        return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
      },
      option: function(item, escape) {
        var label = item.personnel_no || item.name;
        var caption = item.personnel_no ? item.name : null;
        return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
      }
    },
  };

  $.ajax({
  url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/minManagerBoss',
      type: 'GET',
      dataType: 'json',
      error: function() {},
      success: function(res) {
        var newOptions = [];
        var o = {name: res.name, personnel_no: res.personnel_no};
        newOptions.push(o);
        minManagerOptions.options = newOptions;
        var bossSelect = $(".manager-selectize").selectize(minManagerOptions);
        var selectize = bossSelect[0].selectize;
        selectize.setValue(res.personnel_no, false);
    }
  });

  $.ajax({
  url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/minSuperintendentBoss',
      type: 'GET',
      dataType: 'json',
      error: function() {},
      success: function(res) {
        var newOptions = [];
        var o = {name: res.name, personnel_no: res.personnel_no};
        newOptions.push(o);
        minSuperintendentOptions.options = newOptions;
        var bossSelect = $(".superintendent-selectize").selectize(minSuperintendentOptions);
        var selectize = bossSelect[0].selectize;
        selectize.setValue(res.personnel_no, false);
    }
  });
}),

(OvertimePlugins = (function() {
  "use strict";
  return {
    init: function() {
      handleInlineDatePicker(), handleTimePicker(), handleSelectpicker();
    }
  };
})());

</script>

@endpush 
@push('on-ready-scripts') 
OvertimePlugins.init(); 
@endpush