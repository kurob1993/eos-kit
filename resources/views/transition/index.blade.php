@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Pengalihan Approval'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <h4 class="panel-title">Daftar Pengalihan Approval</h4>
    </div>
  @include('layouts._flash')
  <div class="panel-body">
    <a class="btn btn-sm btn-primary" href="{{ route('transition.create') }}">
      <i class="fa fa-plus-circle"></i>
      Pengalihan
    </a>
    <hr>
    <div class="table-responsive">
      {!! $html->table(['class'=>'table table-striped', 'width'=>'100%']) !!}
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
<link href={{ url("/plugins/DataTables/Buttons/css/buttons.dataTables.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/DataTables/Buttons/css/buttons.bootstrap.min.css") }} rel="stylesheet" />
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- DataTables -->
<script src={{ url("/plugins/DataTables/js/jquery.dataTables.min.js") }}></script>
<script src={{ url("/plugins/DataTables/js/dataTables.bootstrap.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Responsive/js/dataTables.responsive.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Buttons/js/dataTables.buttons.min.js") }}></script>
<script src={{ url("/plugins/DataTables/Buttons/js/buttons.bootstrap.min.js") }}></script>
<script src={{ url("/vendor/datatables/buttons.server-side.js") }}></script>

<!-- Generated scripts from DataTables -->
{!! $html->scripts() !!}
@endpush

@push('custom-scripts')
@include('scripts._button-submit-script')
@endpush

@push('on-ready-scripts')
ButtonSubmitPlugins.init();
@endpush