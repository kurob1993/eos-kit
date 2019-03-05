@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Pengalihan Approval'])
<div class="panel panel-prussian">
    <div class="panel-heading">
        <h4 class="panel-title">Tambah Pengalihan Approval</h4>
    </div>

    @include('layouts._flash')

    <div class="panel-body">
        <form action="{{ route('transition.store') }}" method="POST">
            {{ csrf_field() }}
            <div class="col-sm-6">
                <span class="label label-warning">Target Jabatan</span>
                <div class="m-t-3"></div>
                <select id="selectJabatan" class="form-control" name="abbr_jobs" required>
                    @if ( old('abbr_jobs') )
                        <option value="{{ old('abbr_jobs') }}" selected> 
                            {{ old('abbr_jobs') }} 
                        </option>
                    @endif
                </select>
            </div>

            <div class="col-sm-6">
                <span class="label label-primary m-t-3">Target Karyawan</span>
                <div class="m-t-3"></div>
                <select id="selectKaryawan" class="form-control" name="personnel_no" required>
                    @if ( old('personnel_no') )
                        <option value="{{ old('personnel_no') }}" selected> 
                            {{ old('personnel_no') }}
                        </option>
                    @endif
                </select>
            </div>

            <div class="input-daterange input-group col-xs-12 m-t-10" id="datepicker">
                <div class="col-sm-6 m-t-10">
                    <span class="label label-primary">Dari</span>
                    <input type="text" class="form-control m-t-3" 
                        name="start_date" 
                        value="{{ old('start_date') }}" 
                        autocomplete="off"
                        data-date-format="dd/mm/yyyy">
                </div>
        
                <div class="col-sm-6 m-t-10">
                    <span class="label label-primary">Sampai</span>
                    <div class="m-t-3"></div>
                    <input type="text" class="form-control" 
                        name="end_date" 
                        value="{{ old('end_date') }}" 
                        autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 m-t-3">
                <div class="pull-right">
                    <a href="{{ route('transition.index') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-arrow-circle-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2-bootstrap3.min.css') }}">
<link href={{ url("/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />

<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<script src={{ url("/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>

@endpush

@push('custom-scripts')
@include('scripts._transition-script')
@endpush

@push('on-ready-scripts')
TransitionPlugins.init();
@endpush