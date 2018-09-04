  <!-- begin datepicker range -->
  <div id="datepicker-range" data-date-format="yyyy-mm-dd" 
  data-date-start-date="-29d" data-date-end-date="+6d">
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
      <!-- begin start_date field -->
      <div class="col-xs-6">
        <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
          {!! Form::label('start_date', 'Tanggal Mulai') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
            <input type="text" class="form-control" id="start_date" 
            placeholder="Mulai" value="" readonly>
          </div>
          {!! $errors->first('start_date', ' <p class="help-block">:message</p>') !!}
        </div>
      </div>
      <!-- end start_date field -->

      <!-- begin end_date field -->
      <div class="col-xs-6">
        <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
          {!! Form::label('end_date', 'Tanggal berakhir') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
            {!! Form::text('end_date', null, ['class'=>'form-control',
            'placeholder'=>'Berakhir', 'readonly'=>'true', 'id'=>'end_date']) !!}
          </div>
          {!! $errors->first('end_date', ' <p class="help-block">:message</p>') !!}
        </div>
      </div>
      <!-- end end_date field -->
    </div>

    <!-- begin from & to fields -->
    <div class="row">
      <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('from', 'Mulai') !!}
            <div class="input-group bootstrap-timepicker">
                {!! Form::text('from', null, 
                  ['class'=>'form-control', 'placeholder'=>'Tanggal mulai', 
                  'id'=>'from', 'data-parsley-required' => 'true'])
                !!}
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
              </div>
              {!! $errors->first('from', ' <p class="help-block">:message</p>') !!}          
        </div>
      </div>
      <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('to', 'Berakhir') !!}
            <div class="input-group bootstrap-timepicker">
                {!! Form::text('to', null, 
                ['class'=>'form-control', 'placeholder'=>'Tanggal
                berakhir', 'id'=>'to', 'data-parsley-required' => 'true']) 
                !!}
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
              </div>
              {!! $errors->first('to', ' <p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>
    <!-- end from & to fields -->

    <!-- begin overtime_reason field -->
      <div class="form-group">
        {!! Form::label('overtime_reason', 'Alasan Lembur') !!}
        <div class="input-group">
          {!! Form::select('time_event_type_id', $overtime_reason,
          null, ['class'=>'form-control','id'=>'time_event_type_id', 
          'data-parsley-required' => 'true',  'placeholder' => 'Silahkan pilih']); 
        !!}
        </div>
        {!! $errors->first('overtime_reason', ' <p class="help-block">:message</p>') !!}
      </div>
      <!-- end overtime_reason field -->

    <!-- begin approver field -->
    <!-- This field is not sent via form -->
    <div class="form-group{{ $errors->has('approver') ? ' has-error' : '' }}">
        {!! Form::label('approver', 'Atasan') !!}
        <select class="form-control boss-selectize">
            <option value="" selected>Piilh Atasan</option>
          </select>
      </div>
      <!-- end approver field -->

      <!-- begin submit button -->
    <div class="form-group pull-right">
      {!! Form::submit('Kirim Pengajuan Cuti', ['class'=>'btn btn-primary']) !!}
    </div>
    <!-- end submit button -->
  </div>