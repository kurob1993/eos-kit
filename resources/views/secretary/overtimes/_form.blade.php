{{--
Form Elements: personnel_no, start_date, day_assignment, from,
to, overtime_reason, delegation (if have subordinates)
--}}

<div class="col-lg-4">
  @include('layouts._flash')
  <div class="alert alert-success fade in">
    <i class="fa fa-paper-plane pull-left"></i>
    <p>Pastikan bahwa tanggal yang dipilih tidak terdapat hari libur kerja/nasional di dalam jadwal kerja Anda.</p>
    <br />
    <i class="fa fa-calendar pull-left"></i>
    <p>Silahkan isi tanggal pengajuan lembur Anda dengan memilih tanggal pada kalender.</p>
    <br />
    <i class="fa fa-clock-o pull-left"></i>
    <p>Silahkan isi keterangan apakah lembur selesai pada hari yang sama atau keesokan hari.</p>
    <br />
    <i class="fa fa-clock-o pull-left"></i>
    <p>Silahkan isi jam mulai dan berakhir lembur Anda dengan memilih jam pada icon <i class="fa fa-clock-o"></i>.</p>
    <br />
    <i class="fa fa-sliders pull-left"></i>
    <p>Silahkan isi jenis lembur Anda.</p>
    <br />
  </div>
</div>

<div class="col-lg-4">
  <!-- begin personnel_no field -->
  <div class="form-group p-l-5 p-r-5 {{ $errors->has('personnel_no') ? ' has-error' : '' }}">
    {!! Form::label('personnel_no', 'Karyawan') !!}
    {!! Form::select(
      'personnel_no',
      [],
      null,
      [
        'class'=>'form-control sub-selectize',
        'id'=>'personnel_no',
        'data-parsley-required' => 'true',
        'placeholder' => 'Silahkan pilih karyawan'
      ])
    !!}
    {!! $errors->first('personnel_no', '<p class="help-block">:message</p>') !!}
  </div>
  <!-- end personnel_no field -->

  <!-- begin datepicker inline -->
  <h5 class="text-center">Pilih tanggal</h5>
  <div id="datepicker-inline" class="datepicker-range">
    <div></div>
  </div>
  <!-- end datepicker inline -->
</div>

<div class="col-lg-4">

  <div class="row">
    <!-- begin start_date field -->
    <div class="col-xs-6">
      <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
        {!! Form::label('start_date', 'Tanggal Mulai') !!}
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
          {!! Form::text('start_date', null,
          ['class'=>'form-control', 'placeholder'=>'Mulai',
          'id'=>'start_date', 'data-parsley-required'=>'true',
          'readonly' => 'readonly'])
          !!}
        </div>
        {!! $errors->first('start_date', ' <p class="help-block">:message</p>') !!}
      </div>
    </div>
    <!-- end start_date field -->

    <!-- begin day_assignment field -->
    <div class="col-xs-6">
      <div class="form-group{{ $errors->has('day_assignment') ? ' has-error' : '' }}">
        {!! Form::label('day_assignment', 'Berakhir pada') !!}
        {!! Form::select('day_assignment', [ '=' => 'Hari yang sama', '>' => 'Keesokan hari' ],
        null, ['class'=>'form-control', 'id'=>'day_assignment',
        'data-parsley-required' => 'true', 'placeholder' => 'Silahkan pilih']); !!}
        {!! $errors->first('day_assignment', ' <p class="help-block">:message</p>') !!}
      </div>
    </div>
    <!-- end day_assignment field -->
  </div>

  <!-- begin from & to fields -->
  <div class="row">
    <div class="col-xs-6">
      <div class="form-group{{ $errors->has('from') ? ' has-error' : '' }}">
        {!! Form::label('from', 'Mulai') !!}
        <div class="input-group bootstrap-timepicker">
          {!! Form::text('from', null,
          ['class'=>'form-control', 'placeholder'=>'Jam mulai',
          'id'=>'from', 'data-provide'=>'timepicker',
          'data-show-meridian'=>'false',
          'data-minute-step'=>15, 'data-default-time'=>'false',
          'data-parsley-required'=>'true'])
          !!}
          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
        </div>
        {!! $errors->first('from', ' <p class="help-block">:message</p>') !!}
      </div>
    </div>
    <div class="col-xs-6">
      <div class="form-group{{ $errors->has('to') ? ' has-error' : '' }}">
        {!! Form::label('to', 'Berakhir') !!}
        <div class="input-group bootstrap-timepicker">
          {!! Form::text('to', null,
          [ 'class'=>'form-control', 'placeholder'=>'Jam berakhir',
          'id'=>'to', 'data-parsley-required' => 'true',
          'data-provide'=>'timepicker', 'data-show-meridian'=>'false',
          'data-minute-step'=>15, 'data-default-time'=>'false',
          ])
          !!}
          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
        </div>
        {!! $errors->first('to', ' <p class="help-block">:message</p>') !!}
      </div>
    </div>
  </div>
  <!-- end from & to fields -->

  <!-- begin overtime_reason_id field -->
  <div class="form-group{{ $errors->has('overtime_reason_id') ? ' has-error' : '' }}">
    {!! Form::label('overtime_reason_id', 'Alasan Lembur') !!}
    {!! Form::select('overtime_reason_id', $overtime_reason,
    null, ['class'=>'form-control', 'id'=>'overtime_reason_id',
    'data-parsley-required' => 'true', 'placeholder' => 'Silahkan pilih']);
    !!}
    {!! $errors->first('overtime_reason_id', ' <p class="help-block">:message</p>') !!}
  </div>
  <!-- end overtime_reason_id field -->

  <!-- begin superintendent_approver field -->
  <!-- This field is not sent via form -->
  <div class="form-group{{ $errors->has('superintendent_approver') ? ' has-error' : '' }}">
    {!! Form::label('superintendent_approver', 'Superintendent') !!}
    <select class="form-control superintendent-selectize">
      <option value="" selected>Pilih Superintendent</option>
    </select>
  </div>
  <!-- end superintendent_approver field -->

  <!-- begin manager_approver field -->
  <!-- This field is not sent via form -->
  <div class="form-group{{ $errors->has('manager_approver') ? ' has-error' : '' }}">
    {!! Form::label('manager_approver', 'Manager') !!}
    <select class="form-control manager-selectize">
      <option value="" selected>Pilih Manager</option>
    </select>
  </div>
  <!-- end manager_approver field -->

  <!-- begin submit button -->
  <div class="form-group pull-right">
    {!! Form::submit('Kirim Pengajuan Lembur', ['class'=>'btn btn-primary']) !!}
  </div>
  <!-- end submit button -->
</div>