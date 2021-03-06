  {{-- 
    Form Elements: personnel_no, start_date, end_date, deduction,
    permit_type, attachment, note, delegation (if have subordinates)
  --}}

  <!-- begin datepicker range -->
  <div id="datepicker-range" data-date-format="yyyy-mm-dd" 
  data-date-start-date="-10d" data-date-end-date="+15d">
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
    <!-- begin hidden personnel_no field -->
    <div class="form-group{{ $errors->has('personnel_no') ? ' has-error' : '' }}">
      {!! Form::hidden('personnel_no', Auth::user()->employee()->first()->personnel_no, 
        ['class'=>'form-control', 'placeholder'=>'NIK', 'readonly'=>'true', 
          'id'=>'personnel_no'] ) !!}
      {!! $errors->first('personnel_no', '<p class="help-block">:message</p>') !!}
    </div>
    <!-- end hidden personnel_no field -->

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
      <!-- begin permit_type field -->
      <div class="col-xs-12">
        <div class="form-group {{ $errors->has('permit_type') ? ' has-error' : '' }}">
          {!! Form::label('permit_type', 'Jenis Izin') !!}
          {!! Form::select('permit_type', $permit_types, null, ['class'=>'form-control',
            'id'=>'permit_type', 'data-parsley-required' => 'true',  
            'placeholder' => 'Silahkan pilih']); !!}
          {!! $errors->first('permit_type', '
          <p class="help-block">:message</p>') !!}
        </div>
      </div>
      <!-- end permit_type field -->

      <!-- begin attachment field -->
      <div class="col-xs-12">
          <div class="form-group {{ $errors->has('attachment') ? ' has-error' : '' }}">
              {!! Form::label('attachment', 'Lampiran') !!}
              <div class="input-group">
                {!! Form::file('attachment', [  
                'accept' => 'image/*']); !!}
              </div>
              {!! $errors->first('attachment', '
              <p class="help-block">:message</p>') !!}
            </div>
      </div>
      <!-- end attachment field -->
    </div>

    <!-- begin note field -->
    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
      {!! Form::label('note', 'Keterangan Izin') !!} 
      {!! Form::text('note', null, ['class'=>'form-control', 'placeholder'=>'Tulis
      Keterangan Izin', 'id'=>'note', 'data-parsley-required' => 'true',
      'data-parsley-maxlength' => 100]) !!}
      {!! $errors->first('note', '
      <p class="help-block">:message</p>') !!}
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
        {!! $errors->first('delegation', '
        <p class="help-block">:message</p>') !!}            
      </div>
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
  
    <!-- begin submit button -->
    <div class="form-group pull-right">
      {!! Form::submit('Kirim Pengajuan Izin', ['class'=>'btn btn-primary']) !!}
    </div>
    <!-- end submit button -->
  </div>