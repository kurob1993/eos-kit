@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Kuota Cuti Karyawan'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Kuota Cuti Seluruh Karyawan</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    <div class="table-responsive">
        {!! $dataTable->table(['class'=>'table table-striped display nowrap', 'width' => '100%']) !!}
    </div>
  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- DataTables -->
<link href={{ url("/plugins/DataTables/css/dataTables.bootstrap.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Responsive/css/responsive.dataTables.min.css") }} rel="stylesheet" />
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/js/dataTables.bootstrap.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<!-- Generated scripts from DataTables -->
{!! $dataTable->scripts() !!}
<script>
  var add_button = '<a href="{{ route('all_absence_quotas.create') }}" class="btn btn-sm btn-primary m-t-3"> <i class="fa fa-plus" aria-hidden="true"></i> Tambah</a>';
  $('.button').append(add_button);

</script>
@endpush