@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Internal Activity'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Internal Activity</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">
    <div class="row m-b-20">
      <form class="form-horizontal" action="{{ route('internal-activity.store') }}" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
          <label class="control-label col-sm-2" for="jenis">Jenis Kegiatan: </label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="jenis" name="jenis" 
              placeholder="Jenis Kegiatan" required value="{{ old('jenis') }}">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="posisi">Posisi: </label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="posisi" name="posisi"
              placeholder="Posisi" required value="{{ old('posisi') }}">
          </div>
        </div>

        <div class="input-daterange input-group col-xs-12" id="datepicker">
            <div class="form-group">
                <label class="control-label col-sm-2" for="mulai">Mulai: </label>
                <div class="col-sm-10">
                  <input type="text" 
                    class="form-control text-left" 
                    id="mulai" 
                    placeholder="Muali"
                    name="start_date" 
                    value="{{ old('start_date') }}" 
                    autocomplete="off"
                    data-date-format="dd/mm/yyyy" 
                    required>
                </div>
              </div>
    
              <div class="form-group">
                  <label class="control-label col-sm-2" for="selesai">Selesai: </label>
                  <div class="col-sm-10">
                    <input type="text" 
                      class="form-control text-left" 
                      id="selesai" 
                      placeholder="selesai"
                      name="end_date" 
                      value="{{ old('end_date') }}" 
                      autocomplete="off"
                      data-date-format="dd/mm/yyyy" 
                      required>
                  </div>
                </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="keterangan">Keterangan: </label>
          <div class="col-sm-10">
            <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="10" required>{{ old('keterangan') }}</textarea>
          </div>
        </div>

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
<script>
// $('#simpan').click(function() {
//   $(this).attr('disabled','disabled');
// });
</script>


@endpush

@push('custom-scripts')
@include('scripts._transition-script')
@endpush

@push('on-ready-scripts')
TransitionPlugins.init();
@endpush