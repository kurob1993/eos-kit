@extends('layouts.app') 

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Sasaran Kinerja Individu'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Daftar Sasaran Kerja Individu</h4>
  </div>
  <div class="alert alert-success fade in">
     <i class="fa fa-book pull-left"></i>
     <p>Untuk pertanyaan & petunjuk penggunaan hubungi <b>Divisi Human Capital Integration & Adm.</b> telepon <b>72163</b> </p>
     <br>         
    </div>
  @include('layouts._flash')
  <div class="panel-body">
    <a class="btn btn-primary" href="{{ route('ski.create') }}">Tambah</a>
    <select name="stage" id="stage" class="form-control" onchange="cari()" style="width: 200px; display: inline">
      <option value=""> -- Semua Tahapan --</option>
      @foreach ($stage as $item)
          <option value="{{$item->id}}">{{$item->description}}</option>
      @endforeach
    </select>
    <div class="table-responsive m-t-10">
      {!! $html->table(['id'=>'table','class'=>'table table-striped', 'width' => '100%']) !!}
    </div>
  </div>
</div>

<div class="modal fade" id="modal-dialog">
  <div class="modal-dialog" style="width: 85%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Sasaran Kinerja Individu (ID: <span id="title-span"></span>)</h4>
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
<style>
table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid black;
}
</style>
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
<script>
  
function cari() {
  oTable = $('.table').DataTable();
  var stage = $('#stage').val();  
  var cari  = stage;
  oTable.search(cari).draw();
}

function numberOnly(value, id) {
    value = value.replace(/[^0-9\.]/g, '');
    $('#' + id).val(value);
    $('#' + id).css('text-align','right');
}
function setNilai(id) {
    var bobot = Number($('#bobot_'+id).val());
    var skor = Number($('#capaian_'+id).val());

    $('#nilai_'+id).val( (skor*bobot)/10 );
}
</script>

@endpush

@push('custom-scripts')
@include('layouts._modal-detail-script')
@endpush

@push('on-ready-scripts') 
ModalDetailPlugins.init();
@endpush
