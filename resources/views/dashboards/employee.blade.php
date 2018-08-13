@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container')
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Employee Dashboard</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    <div class="table-responsive">
      {!! $html->table(['class'=>'table table-striped']) !!}
    </div>
  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
    <!-- DataTables -->
    <link href="/plugins/DataTables/css/data-table.css" rel="stylesheet" />
    <!-- Selectize -->
    <link href="/plugins/selectize/selectize.css" rel="stylesheet">
    <link href="/plugins/selectize/selectize.bootstrap3.css" rel="stylesheet">
    <!-- Pace -->    
    <script src="/plugins/pace/pace.min.js"></script>
@endpush

@push('plugin-scripts')
<!-- Selectize -->
<script src="/plugins/selectize/selectize.min.js"></script>
<!-- DataTables -->
<script src="/plugins/DataTables/js/jquery.dataTables.js"></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush

@push('custom-scripts')
<script type="text/javascript">
	(handleConfirm = function(){
		$(document.body).on('submit', '.js-confirm', function (e) {
			var $el = $(this)
			var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini?'

      // tampilkan pop up konfirmasi
			var c = confirm(text);

      // kondisi konfirmasi terkait tindakan
      if (c) {
        // menyimpan inputan catatan
        notes = prompt('Silahkan tulis catatan:');
        
        // jika klik cancel batalkan submit
        if (notes == null) {
          
          // batalkan submit
          e.preventDefault();
        } else {

          // tambahkan data notes di dalam POST
          var input = $('<input>').attr('type', 'hidden').attr('name', 'text').val(notes); 
          $el.append($(input));

          // kirimkan submit
          return true;     
        }
      } else {

        // batalkan submit
        e.preventDefault();
      } 

		});	
	}),

	(EmployeePlugins = (function() {
	  "use strict";
	  return {
	    init: function() {
	      handleConfirm();
	    }
	  };
	})());

</script>
@endpush

@push('on-ready-scripts')
EmployeePlugins.init(); 
@endpush