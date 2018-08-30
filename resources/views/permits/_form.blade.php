
  <!-- begin datepicker range -->
  <div id="datepicker-range" data-date-format="yyyy-mm-dd" 
  data-date-start-date="-15d" data-date-end-date="+15d">
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

    <div class="row">
      <div class="col-xs-8">
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
            ['class'=>'form-control', 'placeholder'=>'Tanggal berakhir',
            'readonly'=>'true', 'id'=>'end_date', 'data-parsley-required' => 'true']) 
            !!}
          </div>
          {!! $errors->first('start_date', ' <p class="help-block">:message</p>') !!} 
          {!! $errors->first('end_date', ' <p class="help-block">:message</p>') !!}
        </div>
        <!-- end multiple date input range fields -->
      </div>
      <div class="col-xs-4">
          <!-- begin deduction field -->
          <div class="form-group{{ $errors->has('deduction') ? ' has-error' : '' }}">
            {!! Form::label('deduction', 'Durasi') !!}
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
              {!! Form::text('deduction', null, ['class'=>'form-control',
              'placeholder'=>'Durasi', 'readonly'=>'true', 'id'=>'deduction']) !!}
            </div>
            {!! $errors->first('deduction', '
            <p class="help-block">:message</p>') !!}
          </div>
        <!-- end deduction field -->
      </div>
    </div>

    <div class="row">
      <!-- begin absence_type field -->
      <!-- This field is not sent via form -->
      <div class="col-xs-12">
        <div class="form-group">
          {!! Form::label('absence_type', 'Jenis Izin') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
            {!! Form::select('permit_type', $permit_types, null, ['class'=>'form-control',
             'id'=>'permit_type']); !!}
          </div>
        </div>
      </div>
      <!-- end absence_type field -->
    </div>

    <!-- begin approver field -->
    <!-- This field is not sent via form -->
    <div class="form-group{{ $errors->has('approver') ? ' has-error' : '' }}">
      {!! Form::label('approver', 'Atasan') !!}
      <select class="form-control boss-selectize">
          <option value="" selected>Piilh Atasan</option>
        </select>
    </div>
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

    <!-- begin note field -->
    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
      {!! Form::label('note', 'Keterangan Cuti') !!} 
      {!! Form::text('note', null, ['class'=>'form-control', 'placeholder'=>'Tulis
      Keterangan Cuti', 'id'=>'note', 'data-parsley-required' => 'true']) !!}
      {!! $errors->first('note', '
      <p class="help-block">:message</p>') !!}
    </div>
    <!-- end note field -->

    <!-- begin address field -->
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
      {!! Form::label('address', 'Alamat Selama Cuti') !!} 
      {!! Form::text('address', null, ['class'=>'form-control', 'placeholder'=>'Masukkan
      alamat selama cuti', 'id'=>'address', 'data-parsley-required' => 'true']) !!}
      {!! $errors->first('address', '
      <p class="help-block">:message</p>') !!}
    </div>
    <!-- end address field -->

    <!-- begin submit button -->
    <div class="form-group pull-right">
      {!! Form::submit('Kirim Pengajuan Cuti', ['class'=>'btn btn-primary']) !!}
    </div>
    <!-- end submit button -->
  </div>
