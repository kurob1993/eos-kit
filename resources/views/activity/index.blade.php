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
    
      <div class="row m-b-20">
          <div class="col-sm-4">
              <label for="month">Data:</label>
              <select id="data" class="form-control data" required>
              </select>
          </div>

          <div class="col-sm-2 m-t-25">
              <input type="submit" name="" id="load" value="load" class="btn btn-md btn-primary" required>
          </div>
          
          <div class="col-sm-2">
              <label for="month">Month:</label>
              <select id="month" name="month" class="form-control" required>
                  @foreach ($data['monthList'] as $item)
                  <option value="{{ $item->month }}"> {{ $item->month }}</option>
                  @endforeach
              </select>
          </div>
          <div class="col-sm-2">
              <label for="year">Year:</label>
              <select id="year" name="year" class="form-control" required>
                  @foreach ($data['yearList'] as $item)
                  <option value="{{ $item->year }}"> {{ $item->year }}</option>
                  @endforeach
              </select>
          </div>
          <div class="col-sm-2 m-t-25">
              <input type="submit" name="" id="search" value="search" class="btn btn-md btn-primary" required>
          </div>
      </div>
    
    <div class="table-responsive ">
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
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2-bootstrap3.min.css') }}">
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
<script>
var nik = '{{ Auth::user()->personnel_no }}';
$('#data').select2({
    theme: "bootstrap",
    ajax: {
        url: "{{ url('activity/list') }}/"+nik,
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

$("#load").click(function(){
  var select2 = $('#data').select2('data');
  if(select2) {
    $.get("{{ route('activity.download') }}/"+select2[0].text, function(data, status){
      location.reload();
    });
  }
});

oTable = $('.table').DataTable();
$('#search').click(function(){
    var month = $('#month').val();
    var year = $('#year').val();
    var cari = month+'|'+year;
    oTable.search(cari).draw();
})
</script>
@endpush