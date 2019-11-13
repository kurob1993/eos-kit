@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Komisaris Krakatau Steel'])
<div class="panel panel-prussian">
        <div class="panel-heading">
          <h4 class="panel-title">Struktur Komisaris</h4>
        </div>
        @include('layouts._flash')
        <div class="panel-body">
          <div class="table-responsive">
          <table class="table" id="table">
                <thead>
                    <tr>
                        <th >NIK</th>
                        <th>Nama Komisaris</th>
                        <th>Struktur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item) 
                        <tr>
                            <td>{{$item->empnik}}</td>
                            <td>{{$item->empname}}</td>
                            <td>{{$item->emppostx}}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
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

