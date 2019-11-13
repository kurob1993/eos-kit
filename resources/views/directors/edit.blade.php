@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Direksi Krakatau Steel'])
<div class="panel panel-prussian">
        <div class="panel-heading">
          <h4 class="panel-title">Tambah Direksi</h4>
        </div>

        <div class="panel panel-prussian">
            <div class="alert alert-success fade in">
                <i class="fa fa-book pull-left"></i>
                <p>Pastikan data sesuai dengan permintaan dengan form</p>
                <br>
                <i class="fa fa-book pull-left"></i>
                <p>Jika tanggal pengajuan terdapat hari libur, silahkan bagi dalam formulir terpisah agar tidak terdapat hari libur dalam pengajuan.
                Contoh: Pengajuan tanggal 16/08 sampai dengan 18/08 dimana tanggal 17/08 adalah hari libur, maka pengajuan di ajukan 2 (dua) kali yaitu tanggal 16/08 s/d 16/08 dan pengajuan tanggal 18/08 s/d 18/08.
                </p>          
            </div>
            <div class="panel-body">
                {!! Form::open([
                    'route' => ['direksi.update', $data->empnik],
                    'method' => 'put', 
                    'class'=>'form-horizontal', 
                    'data-parsley-validate' => 'true'
                    ])
                !!}

                {!! Form::hidden('no', Auth::user()->employee()->first()->personnel_no, 
                    ['class'=>'form-control', 'placeholder'=>'NIK', 'readonly'=>'true', 
                    'id'=>'personnel_no'] ) !!}

            
                    <div id="personal-dr">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Informasi Jabatan</h4>

                            </div>
                            <div class="col-md-10 offset-md-1">
                                <div class="form-group ">
                                {!! Form::label('emp_hrp1000_s_short', 'HRP') !!} 
                               <br>
                               <input name="emp_hrp1000_s_short" type="text" class="form-control" value="{{$data->emp_hrp1000_s_short}}">
                               {!! $errors->first('emp_hrp1000_s_short', '<p class="help-block">:message</p>') !!}
                            </div>
                            </div>

                            <div class="col-md-10 offset-md-1">
                                <div class="form-group">
                                {!! Form::label('emppostx', 'Jabatan') !!} 
                                <input name="emppostx" type="text" class="form-control" value="{{$data->emppostx}}">
                                {!! $errors->first('emppostx', '<p class="help-block">:message</p>') !!}
                                </div>
                           </div>

                           <div class="col-md-10 offset-md-1">
                               <div class="form-group">
                                    {!! Form::label('emportx', 'Direktorat') !!} 
                                    <input name="emportx" type="text" class="form-control" value="{{$data->emportx}}">
                                    {!! $errors->first('emportx', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Informasi Personal</h4>                            
                        </div>
                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('empnik', 'Nomor Induk Karyawan') !!} 
                                <input name="empnik" type="text" class="form-control" value="{{$data->empnik}}">
                                {!! $errors->first('empnik', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('empname', 'Nama Lengkap') !!} 
                                <input name="empname" type="text" class="form-control" value="{{$data->empname}}">
                                {!! $errors->first('empname', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('ttl', 'Tempat Tanggal Lahir') !!} 
                                <input name="ttl" type="text" class="form-control" value="{{$data->ttl}}">
                                {!! $errors->first('ttl', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    



                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Informasi Data</h4>                        
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group"> 
                                {!! Form::label('LSTUPDT', 'LSTUPDT') !!} 
                                <input name="LSTUPDT" type="date" class="form-control" value="{{$data->LSTUPDT}}">
                                {!! $errors->first('LSTUPDT', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('emppersk', 'PERSK') !!} 
                                <br>
                                <select class="form-control" name="emppersk" id="emppersk">
                                    <option selected="selected" value="Z">Z</option>
                                    <option value="">Null</option>
                                </select>
                                {!! $errors->first('emppersk', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('empposid', 'POS ID') !!} 
                                <input name="empposid" type="text" class="form-control" value="{{$data->empposid}}">
                                {!! $errors->first('empposid', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>



                        {!! Form::submit('Edit Struktur', ['class'=>'btn btn-info pull-right']) !!}
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush