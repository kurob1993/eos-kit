  {{-- 
    Form Elements: personnel_no, start_date, end_date, deduction,
    absence_type, note, address, delegation (if have subordinates)
  --}}

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
      <!-- begin absence_type field -->
      <div class="col-xs-6">
        <div class="form-group">
          {!! Form::label('absence_type', 'Jenis Cuti') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
            {!! Form::hidden('absence_type_id', $absence_quota->absenceType->id) !!}
            {!! Form::text('absence_type', $absence_quota->absenceType->text, 
            ['class' => 'form-control', 'placeholder' => 'Jenis Cuti', 
            'readonly' => 'readonly', 'id' => 'absence_type']) !!}
          </div>
        </div>
      </div>
      <!-- end absence_type field -->
      <!-- begin number field -->
      <div class="col-xs-3">
        <div class="form-group">
          {!! Form::label('number', 'Sisa Cuti') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" class="form-control" id="number" 
              placeholder="Durasi" value="{{ $absence_quota->balance }}" readonly>
          </div>
        </div>
      </div>
      <!-- end number field -->

      <!-- begin deduction field -->
      <div class="col-xs-3">
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
      </div>
      <!-- end deduction field -->
    </div>

    <!-- begin note field -->
    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
      {!! Form::label('note', 'Keterangan Cuti') !!} 
      {!! Form::text('note', null, ['class'=>'form-control', 'placeholder'=>'Tulis
      Keterangan Cuti', 'id'=>'note', 'data-parsley-required' => 'true',
      'data-parsley-maxlength' => 100]) !!}
      {!! $errors->first('note', '
      <p class="help-block">:message</p>') !!}
    </div>
    <!-- end note field -->

    <!-- begin address field -->
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
      {!! Form::label('address', 'Alamat Selama Cuti') !!} 
      {!! Form::text('address', null, ['class'=>'form-control', 'placeholder'=>'Masukkan
      alamat selama cuti', 'id'=>'address', 'data-parsley-required' => 'true',
      'data-parsley-maxlength' => 100]) !!}
      {!! $errors->first('address', '
      <p class="help-block">:message</p>') !!}
    </div>
    <!-- end address field -->

    <!-- begin approver field -->
    <!-- This field is not sent via form -->
    <div class="form-group{{ $errors->has('approver') ? ' has-error' : '' }}">
        {!! Form::label('approver', 'Atasan') !!}
        <select class="form-control boss-selectize">
            <option value="" selected>Pilih Atasan</option>
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

    <!-- begin submit button -->
    <div class="form-group pull-right">
      {!! Form::submit('Kirim Pengajuan Cuti', ['class'=>'btn btn-primary']) !!}
    </div>
    <!-- end submit button -->
  </div>
