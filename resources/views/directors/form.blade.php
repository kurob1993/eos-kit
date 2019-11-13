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
                    'url' => route('direksi.store'), 
                    'method' => 'post', 
                    'class'=>'form-horizontal', 
                    'data-parsley-validate' => 'true'
                    ])
                !!}

                {!! Form::hidden('no',  Auth::user()->employee()->first()->personnel_no, 
                    ['class'=>'form-control', 'placeholder'=>'NIK', 'readonly'=>'true', 
                    'id'=>'id'] ) !!}

            
                    <div id="personal-dr">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Informasi Jabatan</h4>

                            </div>
                            <div class="col-md-10 offset-md-1">
                                <div class="form-group ">
                                {!! Form::label('emp_hrp1000_s_short', 'HRP') !!} 
                               <br>
                                
                               {!! Form::number('emp_hrp1000_s_short', null, ['class'=>'form-control','placeholder'=>'ex: 1000000000000', 'id'=>'emp_hrp1000_s_short']) !!}
                               {!! $errors->first('emp_hrp1000_s_short', '<p class="help-block">:message</p>') !!}

                            </div>
                            </div>

                            <div class="col-md-10 offset-md-1">
                                <div class="form-group">
                                {!! Form::label('emppostx', 'Jabatan') !!} 
                                
                                {!! Form::text('emppostx', null, ['class'=>'form-control','placeholder'=>'jabatan ex: direktur utama', 'id'=>'emppostx']) !!}
                                {!! $errors->first('emppostx', '<p class="help-block">:message</p>') !!}
                                </div>
                           </div>

                           <div class="col-md-10 offset-md-1">
                               <div class="form-group">
                                    {!! Form::label('emportx', 'Direktorat') !!} 
                                    {!! Form::text('emportx', null, ['class'=>'form-control','placeholder'=>'direktorat ex: direktorat utama', 'id'=>'emportx']) !!}
                                    {!! $errors->first('emportx', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- INFORMASI PERSONAL -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Informasi Personal</h4>                            
                        </div>
                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('empnik', 'Nomor Induk Karyawan') !!} 
                                {!! Form::number('empnik', null, ['class'=>'form-control','placeholder'=>'NIK Direksi / Komisaris', 'id'=>'empnik']) !!}
                                {!! $errors->first('empnik', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('empname', 'Nama Lengkap') !!} 
                                {!! Form::text('empname', null, ['class'=>'form-control','placeholder'=>'Nama Lengkap', 'id'=>'empname']) !!}
                                {!! $errors->first('empname', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('ttl', 'Tempat Tanggal Lahir') !!} 
                                {!! Form::text('ttl', null, ['class'=>'form-control','placeholder'=>'ex : cilegon, 21-01-1980', 'id'=>'ttl']) !!}
                                {!! $errors->first('ttl', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    

                    </div>
                    <!-- END INFORMASI PERSOANL -->

                    <!--START INFORMASI DATA -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Informasi Data</h4>                        
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group"> 
                                {!! Form::label('LSTUPDT', 'LSTUPDT') !!} 
                                {!! Form::date('LSTUPDT', null, ['class'=>'form-control','placeholder'=>'LSTUPDT', 'id'=>'emppostx']) !!}
                                {!! $errors->first('LSTUPDT', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('emppersk', 'PERSK') !!} 
                                <br>
                                <select name="emppersk" class="form-control" id="emppersk">
                                    <option value="Z">Z</option>
                                    <option value="null">Null</option>
                                </select>
                                {!! $errors->first('emppersk', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                {!! Form::label('empposid', 'POS ID') !!} 
                                {!! Form::text('empposid', null, ['class'=>'form-control','placeholder'=>'ex : empposid : 1000', 'id'=>'empposid']) !!}
                                {!! $errors->first('empposid', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <!--END INFORMASI DATA -->



                        {!! Form::submit('Buat Struktur', ['class'=>'btn btn-primary text-right']) !!}
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

