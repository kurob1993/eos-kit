@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Curriculum Vitae'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
      @include('layouts._flash')
      <!-- begin of dashboard nav-tabs  -->
      <ul class="nav nav-tabs nav-tabs-primary nav-justified nav-justified-mobile">
          <li class="active">
              <a href="#tab-leaves" data-toggle="tab" aria-expanded="true"> Data Pribadi
                  @if (false)
                  <span class="badge pull-right m-l-5">
                      <h5>Data Pribadi</h5>
                  </span>
                  @endif
              </a>
          </li>
          <li class="">
              <a href="#tab-permits" data-toggle="tab" aria-expanded="true"> Pendidikan
                  @if (false)
                  <span class="badge pull-right m-l-5">

                  </span>
                  @endif
              </a>
          </li>
          <li class="">
              <a href="#tab-time-events" data-toggle="tab" aria-expanded="true"> Aktivitas
                  @if (false)
                  <span class="badge pull-right m-l-5">

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
                  <h5>Data Pribadi</h5>
                  <div class="table-responsive">
                    {!! $html->table() !!}
                  </div>
              </div>
          </div>
          <!-- end of leaves tab  -->

          <!-- begin of permits tab  -->
          <div class="tab-pane fade" id="tab-permits">
              <div class="panel-body p-0">
                  <div id="permit-chart" class="m-t-5 m-b-5">Fusionchart for permits will be rendered here.</div>
                  <div class="table-responsive">

                  </div>
              </div>
          </div>
          <!-- end of permits tab  -->

          <!-- begin of time-events tab  -->
          <div class="tab-pane fade" id="tab-time-events">
              <div class="panel-body p-0">
                  <div id="time-event-chart" class="m-t-5 m-b-5">Fusionchart for time events will be rendered here.</div>
                  <div class="table-responsive">

                  </div>
              </div>
          </div>
          <!-- end of time-events tab  -->
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
{!! $html->scripts() !!}
@endpush

@push('custom-scripts')

@endpush

@push('on-ready-scripts') 

@endpush