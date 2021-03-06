@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Internal Activity'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Internal Activity</h4>
  </div>
  @include('layouts._flash')
  <div class="alert alert-success fade in">
    <i class="fa fa-paper-plane pull-left"></i>
    <p>Internal Activity adalah pencatatan kegiatan internal karyawan PT. Krakatau Steel (Persero) Tbk. Misal: SK TIM, Panitia Kegiatan internal unit kerja, dll.</p>   
  </div>
  <div class="panel-body">
    <div class="row m-b-20">
      <form class="form-horizontal" id="form" action="{{ route('internal-activity.store') }}" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
          <label class="control-label col-sm-2" for="posisi"> </label>
          <div id="datepicker-range">

            <div class=" col-lg-5">
              <label class="control-label col-sm-2" for="start_date">Mulai </label>
              <input type="text" class="form-control text-center" id="start_date" name="start_date"
                value="{{ old('start_date') }}" placeholder='Pilih Tanggal mulai'
                style="background : white; color:black" readonly>
              {!! $errors->first('start_date', ' <p class="text-danger help-block">:message</p>') !!}

              <div id="datepicker-range-start" class="datepicker-range"></div>
            </div>

            <div class=" col-lg-5">
              <label class="control-label col-sm-2" for="start_date">Berakhir </label>
              <input type="text" class="form-control text-center is-invalid" id="end_date" name="end_date"
                value="{{ old('end_date') }}" placeholder='Pilih Tanggal berakhir'
                style="background : white; color:black" readonly>
              {!! $errors->first('end_date', ' <p class="text-danger help-block">:message</p>') !!}

              <div id="datepicker-range-end" class="datepicker-range"></div>
            </div>

          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="posisi">Posisi: </label>
          <div class="col-sm-10">
            <select class="form-control" id="posisi" name="posisi">
              <option value="">.:: Pilih Posisi ::. {{ old('posisi') }}</option>
                @foreach ($posisi as $item)
                  @if (old('posisi') == $item->id)
                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                  @else
                    <option value="{{$item->id}}">{{$item->name}}</option>
                  @endif
                @endforeach
            </select>
            {{-- <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Posisi"
              value="{{ old('posisi') }}"> --}}
            {!! $errors->first('posisi', ' <p class="text-danger help-block">:message</p>') !!}
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="jenis_kegiatan">Jenis Kegiatan: </label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="jenis_kegiatan" name="jenis_kegiatan"
              placeholder="Contoh: Team Implementasi SAP" value="{{ old('jenis_kegiatan') }}">
            {!! $errors->first('jenis_kegiatan', ' <p class="text-danger help-block">:message</p>') !!}
          </div>
        </div>

        {{-- <div class="form-group">
          <label class="control-label col-sm-2" for="keterangan">Keterangan: </label>
          <div class="col-sm-10">
            <textarea name="keterangan" class="form-control" id="keterangan" cols="30"
              rows="5">{{ old('keterangan') }}</textarea>
            {!! $errors->first('keterangan', ' <p class="text-danger help-block">:message</p>') !!}
          </div>
        </div> --}}

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
@endcomponent
</div>

<!-- end page container -->
@endsection

@push('styles')
<link href={{ url("/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />
@endpush

@push('plugin-scripts')
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<script src={{ url("/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
@endpush

@push('custom-scripts')
@include('scripts._daterange-picker-script',[
'start_date' => "new Date(new Date().getFullYear() - 4, new Date().getMonth(), 1)",
'end_date' => "new Date(new Date().getFullYear() + 4, new Date().getMonth(), 31)"
])
@endpush
@push('on-ready-scripts')
DaterangePickerPlugins.init();
$(document).on('submit','#form',function(){
$('#simpan').attr('disabled','disabled');
});
@endpush