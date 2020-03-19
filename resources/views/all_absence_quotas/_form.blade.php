<!-- begin datepicker range -->
<div id="datepicker-range">
  <div class=" col-lg-4">
    <h5 class="text-center">Pilih tanggal mulai</h5>
    <div id="datepicker-range-start" class="datepicker-range"></div>
  </div>
  <div class=" col-lg-4">
    <h5 class="text-center">Pilih tanggal berakhir</h5>
    <div id="datepicker-range-end" class="datepicker-range"></div>
  </div>
</div>
<!-- end datepicker range -->

<div class="col-lg-4">
  <!-- begin multiple date input range fields -->
  <div class="form-group{{ $errors->has('start_date') | $errors->has('end_date')  ? ' has-error' : '' }}">
    <label>Rentang Tanggal </label>
    <div class="input-group">
      {!! Form::text('start_date', null,
      ['class'=>'form-control', 'placeholder'=>'Tanggal mulai',
      'readonly'=>'true', 'id'=>'start_date', 'data-parsley-required' => 'true'])
      !!}
      <span class="input-group-addon">to</span>
      {!! Form::text('end_date', null,
      ['class'=>'form-control', 'placeholder'=>'Tanggal
      berakhir', 'readonly'=>'true', 'id'=>'end_date', 'data-parsley-required' => 'true'])
      !!}
    </div>
    {!! $errors->first('start_date', ' <p class="help-block">:message</p>') !!}
    {!! $errors->first('end_date', ' <p class="help-block">:message</p>') !!}
  </div>
  <!-- end multiple date input range fields -->

  <div class="row">
    <!-- begin absence_type field -->
    <div class="col-xs-6">
      <div class="form-group">
        {!! Form::label('absence_type', 'Jenis Cuti') !!}
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
          <input type="text" class="form-control" id="number" placeholder="Jenis Cuti value=">
        </div>
      </div>
    </div>
    <!-- end absence_type field -->
    <div class="col-xs-1"></div>
    <!-- begin number field -->
    <div class="col-xs-5">
      <div class="form-group">
        {!! Form::label('number', 'Jumlah Cuti') !!}
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
          <input type="text" class="form-control" id="number" placeholder="Durasi" value="">
        </div>
      </div>
    </div>
    <!-- end number field -->
  </div>

  <!-- begin note field -->
  <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
    {!! Form::label('nama', 'Karyawan') !!}
    {!! Form::text('nama', null, ['class'=>'form-control', 'placeholder'=>'Tulis
    Keterangan Cuti', 'id'=>'note', 'data-parsley-required' => 'true',
    'data-parsley-maxlength' => 100]) !!}
    {!! $errors->first('note', '
    <p class="help-block">:message</p>') !!}
  </div>
  <!-- end note field -->

  <!-- begin submit button -->
  <div class="form-group pull-right">
    {!! Form::submit('Kirim Pengajuan Cuti', ['class'=>'btn btn-primary']) !!}
  </div>
  <!-- end submit button -->
</div>