  {{-- 
    Form Elements: pernr, begda, enda, otype, objid, stext, profile, status
  --}}

  <div class="col-lg-4">
    <div class="row">
        <div class="col-xs-11"> 
        <input name="jumSameLevel" value="{{ $preferSameLevel }}" type="hidden" id="jumSameLevel" />
        <input name="jumNotSameLevel" value="{{ $preferNotSameLevel }}" type="hidden" id="jumNotSameLevel" />
    

        <input name="golongan" value="{{ $level }}" type="hidden" id="level" />
        <input name="periode" value="{{ $periode->id }}" type="text" id="periode" />
        <div class="form-group {{ $errors->has('direktorat') ? ' has-error' : '' }}">
          {!! Form::label('personnel_no', 'Direktorat') !!}
          {!! Form::select(
            'AbbrOrgUnitDirektorat',
            [],
            null,
            [
              'class'=>'form-control sub-selectize',
              'id'=>'AbbrOrgUnitDirektorat',
              'placeholder' => 'Silahkan Pilih Direktorat'
            ])
          !!}
          {!! $errors->first('AbbrOrgUnitDirektorat', '<p class="help-block">:message</p>') !!}
        </div>

        @if($level  == "A" || $level == "B" || $level == "C" || $level == "D" || $level == 'E' )
          <div class="form-group{{ $errors->has('subdit') ? ' has-error' : '' }}">
            {!! Form::label('subdit', 'Subdit') !!}
            {!! Form::select(
              'AbbrOrgUnitSubDirektorat',
              [],
              null,
              [
                'class'=>'form-control subdit-selectize',
                'id'=>'AbbrOrgUnitSubDirektorat',
                'placeholder' => 'Silahkan Pilih Subdit'
              ])
            !!}
            {!! $errors->first('AbbrOrgUnitSubDirektorat', '<p class="help-block">:message</p>') !!}
          </div>
        @endif
      
        @if($level == "B" || $level == "C" || $level == "D" || $level == 'E' )
          <div class="form-group{{ $errors->has('divisi') ? ' has-error' : '' }}">
            {!! Form::label('divisi', 'Divisi') !!}
            {!! Form::select(
              'AbbrOrgUnitDivisi',
              [],
              null,
              [
                'class'=>'form-control divisi-selectize',
                'id'=>'AbbrOrgUnitDivisi',
                'placeholder' => 'Silahkan Pilih Divisi'
              ])
            !!}
            {!! $errors->first('AbbrOrgUnitDivisi', '<p class="help-block">:message</p>') !!}
          </div>
        @endif

        @if($level == "C" || $level == "D" || $level == 'E' )
          <div class="form-group{{ $errors->has('dinas') ? ' has-error' : '' }}">
            {!! Form::label('dinas', 'Dinas') !!}
            {!! Form::select(
              'AbbrOrgUnitDinas',
              [],
              null,
              [
                'class'=>'form-control dinas-selectize',
                'id'=>'AbbrOrgUnitDinas',
                'placeholder' => 'Silahkan Pilih Dinas'
              ])
            !!}
            {!! $errors->first('AbbrOrgUnitDinas', '<p class="help-block">:message</p>') !!}
          </div>
        @endif

        @if($level == "D" || $level == 'E' )
          <div class="form-group{{ $errors->has('seksi') ? ' has-error' : '' }}">
            {!! Form::label('seksi', 'Seksi') !!}
            {!! Form::select(
              'AbbrOrgUnitSeksi',
              [],
              null,
              [
                'class'=>'form-control seksi-selectize',
                'id'=>'AbbrOrgUnitSeksi',
                'placeholder' => 'Silahkan Pilih Seksi'
              ])
            !!}
            {!! $errors->first('AbbrOrgUnitSeksi', '<p class="help-block">:message</p>') !!}
          </div>
        @endif
    </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="row">
      <div class="col-xs-12">
        <!-- <div class="form-group{{ $errors->has('profile') ? ' has-error' : '' }}">
          {!! Form::label('profile', 'Pilih Kategori') !!}
          <select class="form-control profile">
            <option value="" selected>Pilih Kategori</option>
            <option value="A042" selected>Jabatan yang disukai</option>
            <option value="A043" selected>Jabatan yang tidak disukai</option>
          </select>
        </div> -->

        <div class="form-group{{ $errors->has('posisi') ? ' has-error' : '' }}">
          {!! Form::label('posisi', 'Pilih Jabatan') !!}
          {!! Form::select(
            'AbbrPosition',
            [],
            null,
            [
              'class'=>'form-control posisi-selectize',
              'id'=>'AbbrPosition',
            ])
          !!}
          {!! $errors->first('AbbrPosition', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{{ $errors->has('abbrPosisitionNew') ? ' has-error' : '' }}">
          {!! Form::label('abbrPosisitionNew', 'Jabatan terpilih') !!}
          {!! Form::select(
            'abbrPosisitionNew[]',
            [],
            null,
            [
              'class'=>'form-control test-selectize',
              'id'=>'test-selectize',
              'data-parsley-required' => 'true',
              'multiple'
            ])
          !!}
          {!! $errors->first('abbrPosisitionNew', '<p class="help-block">:message</p>') !!}
        </div>

        <!-- begin submit button -->
        <div class="form-group pull-right">
            {!! Form::submit('Kirim Data', ['class'=>'btn btn-primary']) !!}
        </div>
        <!-- end submit button -->
      </div>
    </div>
  </div>