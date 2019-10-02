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
  <!-- begin personnel_no field -->
  <div class="form-group{{ $errors->has('personnel_no') ? ' has-error' : '' }}">
    {!! Form::hidden('personnel_no', Auth::user()->employee()->first()->personnel_no,
    ['class'=>'form-control', 'placeholder'=>'NIK', 'readonly'=>'true',
    'id'=>'personnel_no'] ) !!}
    {!! $errors->first('personnel_no', '<p class="help-block">:message</p>') !!}
  </div>
  <!-- end personnel_no field -->

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
    <!-- begin deduction field -->
    <div class="col-xs-4" style="padding-right: 25px">
      <div class="form-group{{ $errors->has('deduction') ? ' has-error' : '' }}">
        {!! Form::label('deduction', 'Durasi') !!}
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
          {!! Form::text('durasi', null, ['class'=>'form-control',
          'placeholder'=>'Durasi', 'readonly'=>'true', 'id'=>'deduction']) !!}
        </div>
        {!! $errors->first('durasi', '
        <p class="help-block">:message</p>') !!}
      </div>
    </div>
    <!-- end deduction field -->

    <div class="col-xs-4" style="padding-right: 25px">
      <div class="form-group">
        <label for="gol">Gol</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-users"></i></span>
          <input type="text" id="gol" name="gol" class="form-control" readonly value="{{$gol}}" placeholder="Gol">
        </div>
      </div>
    </div>

    <div class="col-xs-4">
      <div class="form-group">
        <label for="cc">Cost Center</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-usd"></i></span>
          <input type="text" id="cc" name="cc" class="form-control" readonly value="{{$cc}}" placeholder="Cost Center">
        </div>
      </div>
    </div>

  </div>

  <div class="form-group">
    <label for="nohp">Jenis SPD</label>
    <select name="travel-type" id="travel-type" class="form-control" required>
      <option value="">.:: Pilih Jenis SPD ::.</option>
      @foreach ($travelType as $item)
        <option value="{{$item->subtype}}">{{$item->text}}</option>
      @endforeach
    </select>
  </div>

  <span id="div_lampiran"></span>

  <div class="row">
    <div class="col-xs-6" style="padding-right: 25px">
      <div class="form-group">
        <label for="tujuan">Tujuan</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-plane"></i></span>
          <select name="tujuan" id="tujuan" class="form-control" required>
            <option value="">.:: Pilih Tujuan ::.</option>
            <option value="dalam">Dalam Negri</option>
            <option value="luar">Luar Negri</option>
          </select>
        </div>
      </div>
    </div>

    <div class="col-xs-6" id="div_kota"> </div>

  </div>

  <div class="row">

    <div class="col-xs-6" style="padding-right: 30px">
      <div class="form-group">
        <label for="kendaraan">Kendaraan</label>
        <select name="kendaraan" id="kendaraan" class="form-control" required>
          <option value="">.:: Pilih Kendaraan ::.</option>
          <option value="dinas">Dinas</option>
          <option value="umum">Umum</option>
        </select>
      </div>
    </div>

    <div class="col-xs-6" id="div_jenis_kendaraan"> </div>

  </div>

  <!-- begin note field -->
  <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
    {!! Form::label('note', 'Keperluan') !!}
    {!! Form::text('keperluan', null, ['class'=>'form-control', 'placeholder'=>'Tulis
    Keperluan', 'id'=>'note', 'data-parsley-required' => 'true',
    'data-parsley-maxlength' => 100]) !!}
    {!! $errors->first('note', '
    <p class="help-block">:message</p>') !!}
  </div>
  <!-- end note field -->

  <!-- begin approver field -->
  <!-- This field is not sent via form -->
  <div class="form-group{{ $errors->has('approver') ? ' has-error' : '' }}">
    {!! Form::label('approver', 'Atasan') !!}
    <select class="form-control boss-selectize" name="minManagerBoss" required> 
      <option value="" selected>.:: Pilih Atasan ::.</option>
    </select>
  </div>

  <span id="div_manager_ga"></span>
  <!-- end approver field -->

  <!-- begin delegation field -->
  @if ($can_delegate)
  <div class="form-group">
    {!! Form::label('delegation', 'Penerima Limpahan Wewenang') !!}
    {!! Form::select('delegation', [], null,
    [ 'placeholder' => 'Pilih karyawan',
    'class' => 'form-control sub-selectize',
    'data-parsley-required' => 'true'] ) !!}
  </div>
  @endif
  <!-- end delegation field -->

  <!-- begin submit button -->
  <div class="form-group pull-right">
    {!! Form::submit('Kirim Pengajuan Cuti', ['class'=>'btn btn-primary']) !!}
  </div>
  <!-- end submit button -->
</div>