
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
      <!-- This field is not sent via form -->
      <div class="col-xs-6">
        <div class="form-group">
          {!! Form::label('absence_type', 'Jenis Cuti') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
            <input type="text" class="form-control" id="absence_type" 
            placeholder="Jenis Cuti" value="" readonly>
          </div>
        </div>
      </div>
      <!-- end absence_type field -->

      <!-- begin number field -->
      <!-- This field is not sent via form -->
      <div class="col-xs-3">
        <div class="form-group">
          {!! Form::label('number', 'Sisa Cuti') !!}
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" class="form-control" id="number" 
              placeholder="Durasi" value="" readonly>
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
