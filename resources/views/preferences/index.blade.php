@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Preferences and Dislikes'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Daftar jabatan yang disukai dan tidak disukai</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    @if($datacek > 0)
      <p> <a class="btn btn-primary" href="{{ route('preference.create') }}">Tambah</a> </p>
    @endif
    <div class="row" style="margin-bottom: 20px">
      <div>
          <div class="col-sm-3">
              <label for="year">Periode:</label>
              <select id="year" name="year" class="form-control" required>
                  <option selected value=""> .:: All Periode ::. </option>
                  @foreach ($dataperiode as $item)
                      <option value="{{ $item->start_date }} {{ $item->finish_date }}">{{ $item->start_date }} s/d {{ $item->finish_date }}</option>
                  @endforeach
              </select>
          </div>
          {{-- <div class="col-sm-4">
              <label for="search">Search:</label>
              <input type="text" name="" id="text" class="form-control">
          </div> --}}
          <div class="col-sm-4 m-t-25">
              <input type="submit" name="" id="search" value="search" class="btn btn-md btn-primary" required>
          </div>
      </div>
    </div>
    <div class="table-responsive">
      {!! $html->table(['class'=>'table table-striped display nowrap', 'width'=>'100%']) !!}
    </div>
  </div>
</div>
<div class="modal fade" id="modal-dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Izin (ID: <span id="title-span"></span>)</h4>
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
<script>
    oTable = $('.table').DataTable();
    $('#search').click(function(){
        var year = $('#year').val();
        var text = $('#text').val();
        var cari = year;
        oTable.search(cari).draw();
    })
</script>
@endpush

@push('custom-scripts')
@include('layouts._modal-detail-script')
@endpush

@push('on-ready-scripts') 
ModalDetailPlugins.init();
@endpush