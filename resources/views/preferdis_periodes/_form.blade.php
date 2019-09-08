  <div class="col-lg-8">
    <div class="row">
      <div class="col-md-4">
        <!-- begin multiple date input range fields -->
        <div class="form{{ $errors->has('start_date') | $errors->has('end_date')  ? ' has-error' : '' }}">
          <label>Tanggal Mulai </label>
            {!! Form::text('start_date', isset($string_startDate) ? $string_startDate : null, 
              ['class'=>'form-control', 'placeholder'=>'Tanggal mulai', 
               'id'=>'start_date', 'data-parsley-required' => 'true'])
            !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form{{ $errors->has('start_date') | $errors->has('end_date')  ? ' has-error' : '' }}">
          <label>Tanggal Berakhir </label>
            {!! Form::text('end_date', isset($string_finishDate) ? $string_finishDate : null, 
              ['class'=>'form-control', 'placeholder'=>'Tanggal berakhir', 
              'id'=>'end_date', 'data-parsley-required' => 'true'])
            !!}
        </div>
      </div>
        <!-- begin submit button -->
        <div class="col-md-3">
            <label></label>
          <div class="form">
            {!! Form::submit('Simpan Data', ['class'=>'btn btn-primary']) !!}
          </div>
        </div>
        <!-- end submit button -->
        <!-- end multiple date input range fields -->
      </div>
    </div>