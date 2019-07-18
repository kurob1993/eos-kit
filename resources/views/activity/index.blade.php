@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Laporan Aktivitas'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Laporan Aktivitas</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    
      <div class="row m-b-25">
          <div class="col-sm-3">
              <label for="month">Data:</label>
              <select id="month" name="month" class="form-control" required>
                  <option selected value=""> .:: Select one data ::. </option>
                  {{-- @foreach ($data['monthList'] as $item)
                  <option value="{{ $item->month }}"> {{ $item->month }}</option>
                  @endforeach --}}
              </select>
          </div>

          <div class="col-sm-2 m-t-25">
              <input type="submit" name="" id="load" value="load" class="btn btn-md btn-primary" required>
          </div>
          
          <div class="col-sm-2">
              <label for="month">Month:</label>
              <select id="month" name="month" class="form-control" required>
                  <option selected value=""> .:: All Month ::. </option>
                  {{-- @foreach ($data['monthList'] as $item)
                  <option value="{{ $item->month }}"> {{ $item->month }}</option>
                  @endforeach --}}
              </select>
          </div>
          <div class="col-sm-2">
              <label for="year">Year:</label>
              <select id="year" name="year" class="form-control" required>
                  <option selected value=""> .:: All Year ::. </option>
                  {{-- @foreach ($data['yearList'] as $item)
                  <option value="{{ $item->year }}"> {{ $item->year }}</option>
                  @endforeach --}}
              </select>
          </div>
          <div class="col-sm-2 m-t-25">
              <input type="submit" name="" id="search" value="search" class="btn btn-md btn-primary" required>
          </div>
      </div>
    
    <div class="table-responsive">
        {!! $html->table(['class'=>'table table-striped', 'width'=>'100%']) !!}
    </div>
  </div>
</div>
<div class="modal fade" id="modal-dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Cuti (ID: <span id="title-span"></span>)</h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/jquery.dataTables.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Responsive/css/responsive.dataTables.min.css") }} rel="stylesheet" />
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush