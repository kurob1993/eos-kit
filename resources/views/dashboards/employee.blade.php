@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Employee Dashboard'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <h4>Persetujuan Karyawan</h4>
    @include('layouts._flash')
      <!-- begin of dashboard nav-tabs  -->
      <ul class="nav nav-tabs">
          <li class="active">
              <a href="#tab-leaves" data-toggle="tab" aria-expanded="true"> Cuti
                  @if ($countLeaveApprovals > 0) 
                  <span class="badge pull-right m-l-5">
                      {{$countLeaveApprovals}} 
                  </span>
                  @endif
              </a>
          </li>
          <li class="">
              <a href="#tab-permits" data-toggle="tab" aria-expanded="true"> Izin 
                  @if ($countPermitApprovals > 0) 
                  <span class="badge pull-right m-l-5">
                      {{$countPermitApprovals}} 
                  </span>
                  @endif
              </a>
          </li>
          <li class="">
              <a href="#tab-time-events" data-toggle="tab" aria-expanded="true"> Slash
                  @if ($countTimeEventApprovals > 0) 
                  <span class="badge pull-right m-l-5">
                      {{$countTimeEventApprovals}} 
                  </span>
                  @endif                 
              </a>
          </li>
          <li class="">
              <a href="#tab-overtimes" data-toggle="tab" aria-expanded="true"> Lembur
                  @if ($countOvertimeApprovals > 0) 
                  <span class="badge pull-right m-l-5">
                      {{$countOvertimeApprovals}} 
                  </span>
                  @endif 
              </a>
          </li>
      </ul>
      <!-- end of dashboard nav-tabs  -->

      <!-- begin of tab-content  -->
      <div class="tab-content">
          <!-- begin of leaves tab  -->
          <div class="tab-pane fade active in" id="tab-leaves">
              <div class="panel-body p-0">
                  <div class="table-responsive">
                    {!! $absenceTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
                  </div>
                </div>
          </div>
          <!-- end of leaves tab  -->

          <!-- begin of permits tab  -->
          <div class="tab-pane fade" id="tab-permits">
              <div class="panel-body p-0">
                  <div class="table-responsive">
                    {!! $attendanceTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
                  </div>
                </div>
          </div>
          <!-- end of permits tab  -->

          <!-- begin of time-events tab  -->
          <div class="tab-pane fade" id="tab-time-events">
              <div class="panel-body p-0">
                {!! $timeEventTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
              </div>
          </div>
          <!-- end of time-events tab  -->

          <!-- begin of overtimes tab  -->
          <div class="tab-pane fade" id="tab-overtimes">
              <div class="panel-body p-0">
                {!! $attendanceQuotaTable->table(['class'=>'table table-striped', 'width' => '100%']) !!}
              </div>
          </div>
          <!-- end of overtimes tab  -->

      </div>
      <!-- begin of tab-content  -->
  </div>

</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/jquery.dataTables.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Responsive/css/responsive.dataTables.min.css") }} rel="stylesheet" />
  <!-- Selectize -->
  <link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
  <link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
  <!-- Pace -->    
  <script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- Selectize -->
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $absenceTable->scripts() !!}
{!! $attendanceTable->scripts() !!}
{!! $attendanceQuotaTable->scripts() !!}
{!! $timeEventTable->scripts() !!}
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