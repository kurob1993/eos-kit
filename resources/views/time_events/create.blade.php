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
<script>
  (handleInlineDatePicker) = function() {
    "use strict";
    $("#datepicker-inline").datepicker({ 
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      startDate: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
      endDate: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 5),
      // datesDisabled: ['2018-09-01'],
     });
    $('#datepicker-inline').on('changeDate', function() {
      $('#check_date').val(
          $('#datepicker-inline').datepicker('getFormattedDate')
      );
    });    
  },
  (handleTimePicker) = function() {
    "use strict";
    $("#timepicker").timepicker({ })
  },
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
  
   @if (Auth::user()->employee()->first()->hasSubordinate())
  
  var subOptions = {
    persist: false,
    valueField: "name",
    labelField: "personnel_no",
    searchField: ["personnel_no", "name"],
    options: [    ],
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

(TimeEventPlugins = (function() {
  "use strict";
  return {
    init: function() {
      handleSelectpicker(), handleTimePicker(), handleInlineDatePicker();
    }
  };
})());

</script>

@endpush 

@push('on-ready-scripts') 
TimeEventPlugins.init(); 
@endpush