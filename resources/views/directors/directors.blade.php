@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Direksi Krakatau Steel'])
<div class="panel panel-prussian">
        <div class="panel-heading">
          <h4 class="panel-title">Struktur Direksi</h4>
        </div>
        @include('layouts._flash')

        
        <div class="panel-body">
        <div class="row">
            
            <div class="col-lg-12 col-xl-9">
                
                <!-- begin of dashboard nav-tabs  -->
                <a class="btn btn-primary" href="{{route('direksi.create')}}" ><i class="fa fa-user-plus"></i>List Baru</a>
                <br><br>
                <ul class="nav nav-tabs nav-tabs-primary nav-justified nav-justified-mobile">
                    <li class="active">
                        <a href="#directors" data-toggle="tab" aria-expanded="true">  <h5><b>Direksi</b></h5>
                         
                        </a>
                    </li>
                    <li class="">
                        <a href="#commisary" data-toggle="tab" aria-expanded="true"> 
                            <h5><b>Komisaris</b></h5>
                          
                        </a>
                    </li>
                </ul>
                <!-- end of dashboard nav-tabs  -->

                <!-- begin of tab-content  -->
                <div class="tab-content">
                    <!-- begin of directors tab  -->
                    <div class="tab-pane fade active in" id="directors">
                        <div class="panel-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table">
                                    <thead>
                                        <tr >
                                            <th >NIK</th>
                                            <th>Nama Direksi</th>
                                            <th>Jabatan</th>
                                            <th>Direktorat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dir as $item) 
                                            <tr>
                                                <td>{{$item->empnik}}</td>
                                                <td>{{$item->empname}}</td>
                                                <td>{{$item->emppostx}}</td>
                                                <td>{{$item->emportx}}</td>
                                                <td>
                                                    <div>
                                                        <a href="{{route('direksi.edit', ['empnik' => $item->empnik])}}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                                                        <span>
                                                            <form action="{{ route('direksi.destroy', ['empnik' => $item->empnik]) }}" method="POST" style="display: inline !important;">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button class="btn btn-danger btn-xs m-2">
                                                                    <i class="fa fa-times"></i> 
                                                                </button>
                                                            </form>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end of directors tab  -->

                    <!-- begin of commisary tab  -->
                    <div class="tab-pane fade" id="commisary">
                        <div class="panel-body p-0">
                            <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr >
                                        <th >NIK</th>
                                        <th>Nama Komisaris</th>
                                        <th>Jabatan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($com as $item) 
                                        <tr>
                                            <td>{{$item->empnik}}
                                            </td>
                                            <td>{{$item->empname}}</td>
                                            <td>{{$item->emppostx}}</td>
                                            
                                            <td class="btn-col" style="white-space: nowrap">
												<a href="{{route('direksi.edit', ['empnik' => $item->empnik])}}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                                                <form action="{{ route('direksi.destroy', ['empnik' => $item->empnik]) }}" method="POST" style="display: inline !important;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button class="btn btn-danger btn-xs m-2">
                                                        <i class="fa fa-times"></i> 
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                        </div>
                    </div>
                    <!-- end of commisary tab  -->


                </div>
                <!-- begin of tab-content  -->
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

