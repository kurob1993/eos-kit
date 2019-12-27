<div class="col-lg-12">
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
  
    <div class="form-group {{ $errors->has('periode') ? ' has-error' : '' }}">
      <div class="col-xs-6 p-l-5 p-r-5">
        <label for="">Pilih Bulan</label>
        <select name="bulan" id="bulan" class="form-control">
          <option value="06" selected>Juni</option>
          <option value="12">Desember</option>
        </select>
      </div>
      <div class="col-xs-6 p-l-5 p-r-5">
        <label for="">Pilih Tahun</label>
        <select name="tahun" id="tahun" class="form-control">
          @for ($i = 0; $i < 10; $i++) @if ($i==0) <option value="{{date('Y') - $i}}" selected>{{date('Y') - $i}}</option>
            @else
            <option value="{{date('Y') - $i}}">{{date('Y') - $i}}</option>
            @endif
            @endfor
        </select>
      </div>
    </div>
    
    <!-- begin superintendent_approver field -->
    <!-- This field is not sent via form -->
    <div class="p-l-5 p-r-5 form-group{{ $errors->has('superintendent_approver') ? ' has-error' : '' }}">
      {!! Form::label('superintendent_approver', 'Superintendent') !!}
      <select class="form-control superintendent-selectize" name="superintendent">
        <option value="" selected>Pilih Superintendent</option>
      </select>
    </div>
    <!-- end superintendent_approver field -->
  
    <!-- begin manager_approver field -->
    <!-- This field is not sent via form -->
    <div class="p-l-5 p-r-5 form-group{{ $errors->has('manager_approver') ? ' has-error' : '' }}">
      {!! Form::label('manager_approver', 'Manager') !!}
      <select class="form-control manager-selectize" name="manager">
        <option value="" selected>Pilih Manager</option>
      </select>
    </div>
  
    <div class="p-l-5 p-r-5 form-group">
      <button id="btn-perilaku"
        type="button" 
        class="btn btn-warning" 
        data-backdrop="static" 
        data-toggle="modal" 
        data-target="#modalPrilaku"
        style="display: none"
        onclick="">
        INPUT SKI
      </button>
    </div>
</div>
  
  <!-- Modal perilaku -->
  <div id="modalPrilaku" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 85%">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Perilaku</h4>
        </div>
        <div class="modal-body">

          <div class="alert alert-success fade in">
            <div class="row">
              <div class="col-md-3">
                <p>Di bawah TARGET</p>
                <p>SKOR (0 - 4.5)</p>
                <p>Pencapaian 0% - < 70%</p>
              </div>
              <div class="col-md-3">
                <p>Mendekati TARGET</p>
                <p>SKOR 4.6 - 6.5</p>
                <P>Pencapaian 70% - < 90% </p>
              </div>
              <div class="col-md-3">
                <p>Memenuhi TARGET</p>
                <p>SKOR 6.6 - 8.0</p>
              </div>
              <div class="col-md-3">
                <p>Melebihi TARGET</p>
                <p>SKOR 8.1-10</P>
              </div>
            </div>
          </div>
          
          @include('ski._tableShareKpi')
          <div class="m-10"></div>
          @include('ski._tableKpiHasil')
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="kirimPerilaku" >
            Kirim
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>