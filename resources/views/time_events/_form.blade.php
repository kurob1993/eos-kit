
   {{-- 
    Form Elements: personnel_no, check_date, check_time,
    time_event_type, note, delegation (if have subordinates)
  --}}

  <!-- begin datepicker inline -->
  <div class="col-lg-4">
    <h5 class="text-center">Pilih tanggal</h5>
    <div id="datepicker-inline" class="datepicker-range" data-date-today-highlight='true' 
    data-date-format="yyyy-mm-dd" data-date-start-date="-10d" data-date-end-date="+15d">
      <div></div>
    </div>
  </div>
  <!-- end datepicker inline -->

  <div class="col-lg-4">
    <!-- begin hidden personnel_no field -->
    <div class="form-group{{ $errors->has('personnel_no') ? ' has-error' : '' }}">
      {!! Form::hidden('personnel_no', Auth::user()->employee()->first()->personnel_no, 
        ['class'=>'form-control', 'placeholder'=>'NIK', 'readonly'=>'true', 
          'id'=>'personnel_no'] ) !!}
      {!! $errors->first('personnel_no', '<p class="help-block">:message</p>') !!}
    </div>
    <!-- end hidden personnel_no field -->

    <div class="row">
      <div class="col-xs-6">
        <!-- begin check_date fields -->
        <div class="form-group{{ $errors->has('check_date') | $errors->has('end_date')  ? ' has-error' : '' }}">
          <label>Tanggal</label>
          <div class="input-group">
            {!! Form::text('check_date', null, 
              ['class'=>'form-control', 'placeholder'=>'Tanggal', 
              'readonly'=>'true', 'id'=>'check_date', 'data-parsley-required' => 'true'])
            !!}
          </div>
          {!! $errors->first('check_date', ' <p class="help-block">:message</p>') !!} 
        </div>
        <!-- end check_date fields -->
      </div>
      <div class="col-xs-6">
          <!-- begin check_time field -->
          <div class="form-group{{ $errors->has('check_time') ? ' has-error' : '' }}">
              {!! Form::label('check_time', 'Jam') !!}
              <div class="input-group bootstrap-timepicker">
                  {!! Form::text('check_time', null, 
                  ['id'=>'timepicker', 'class'=>'form-control',
                   'data-provide'=>'timepicker', 'data-show-meridian'=>'false',
                   'data-minute-step'=>15, 'data-default-time'=>'false',
                   'data-parsley-required'=>'true']) !!}
                  <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
              </div>
              {!! $errors->first('check_time', '<p class="help-block">:message</p>') !!}
            </div>
          </div>
        <!-- end check_time field -->
      </div>

      <!-- begin time_event_type field -->
      <div class="form-group">
        {!! Form::label('time_event_type', 'Check-in / Check-out') !!}
        {!! Form::select('time_event_type', ['P10' => 'Check-in', 'P20' => 'Check-out'],
          null, ['class'=>'form-control','id'=>'time_event_type', 
          'data-parsley-required' => 'true',  'placeholder' => 'Silahkan pilih']); 
        !!}
        {!! $errors->first('time_event_type', '<p class="help-block">:message</p>') !!}
      </div>
       <!-- end time_event_type field -->
  
      <!-- begin note field -->
      <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
        {!! Form::label('note', 'Keterangan Izin') !!} 
        {!! Form::text('note', null, ['class'=>'form-control', 'placeholder'=>'Tulis
        Keterangan Izin', 'id'=>'note', 'data-parsley-required' => 'true']) !!}
        {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
      </div>
      <!-- end note field -->

      <!-- begin delegation field -->
      @if ($can_delegate)
      <div class="form-group">
        {!! Form::label('delegation', 'Penerima Limpahan Wewenang') !!}
        {!! Form::select('delegation', [], null,
          [ 'placeholder' => 'Pilih karyawan', 
            'class' => 'form-control sub-selectize',
            'data-parsley-required' => 'true'] ) !!}
      </div>
      {!! $errors->first('delegation', '<p class="help-block">:message</p>') !!}
      @endif
      <!-- end delegation field -->
      
      <!-- begin approver field -->
      <!-- This field is not sent via form -->
      <div class="form-group{{ $errors->has('approver') ? ' has-error' : '' }}">
          {!! Form::label('approver', 'Atasan') !!}
          <select class="form-control boss-selectize">
              <option value="" selected>Piilh Atasan</option>
            </select>
        </div>
        <!-- end approver field -->
    </div>

    <!-- begin submit button -->
    <div class="form-group pull-right">
      {!! Form::submit('Kirim Pengajuan Izin', ['class'=>'btn btn-primary']) !!}
    </div>
    <!-- end submit button -->
  </div>