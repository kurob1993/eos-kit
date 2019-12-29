<div class="col-lg-12">
    <!-- begin personnel_no field -->
    <div class="form-group p-l-5 p-r-5 {{ $errors->has('personnel_no') ? ' has-error' : '' }}">
      {!! Form::label('personnel_no', 'Karyawan') !!}
      <select name="personnel_no" id="personnel_no" class="form-control">
        <option value="{{ Auth::user()->personnel_no}}" selected>{{ Auth::user()->name}}</option>
      </select>
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
      <select name="superintendent" id="superintendent_approver" class="form-control">
        <option value="{{ Auth::user()->employee->minSptBossWithDelegation()->personnel_no }}" selected>{{ Auth::user()->employee->minSptBossWithDelegation()->name}}</option>
      </select>
    </div>
    <!-- end superintendent_approver field -->
  
    <!-- begin manager_approver field -->
    <!-- This field is not sent via form -->
    <div class="p-l-5 p-r-5 form-group{{ $errors->has('manager_approver') ? ' has-error' : '' }}">
      {!! Form::label('manager_approver', 'Manager') !!}
      <select name="manager" id="manager_approver" class="form-control">
        <option value="{{ Auth::user()->employee->minManagerBossWithDelegation()->personnel_no }}" selected>{{ Auth::user()->employee->minManagerBossWithDelegation()->name}}</option>
      </select>
    </div>
  
    <div class="p-l-5 p-r-5 form-group">
      <button id="btn-perilaku"
        type="button" 
        class="btn btn-warning" 
        data-backdrop="static" 
        data-toggle="modal" 
        data-target="#modalPrilaku"
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
          <h4 class="modal-title">INPUT SKI</h4>
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

          <table class="table-responsive" style="width: 100%">
              <thead>
                  <tr style="background-color: darkcyan; color: white">
                      <th class="text-center" style="width: 2%">NO</th>
                      <th class="text-center" style="width: 8%">Asepk Penilaian</th>
                      <th class="text-center" style="width: 4%">Kode</th>
                      <th class="text-center" style="width: 20%">Sasaran Prestasi Kerja</th>
                      <th class="text-center" style="width: 10%">Ukuran Prestasi Kerja</th>
                      <th class="text-center" style="width: 6%">Bobot</th>
                      <th class="text-center" style="width: 6%">Keterangan</th>
                      <th class="text-center" style="width: 6%">Skor</th>
                      <th class="text-center" style="width: 8%">Nilai</th>
                      <th class="text-center" style="width: 8%">Aksi</th>
                  </tr>
              </thead>
              <tbody id="tbodyPerilaku">
                  @include('ski.kpi_share._table')
                  @include('ski.kpi_hasil._table')
                  @include('ski.kpi_proses._table')
                  @include('ski.kpi_perilaku._table')
                  @include('ski.kpi_leadership._table')
              </tbody>
          </table>
          <input type="hidden" id="last_id_kpi_hasil" value="0" style="width: 100%">
          <input type="hidden" id="last_id_kpi_proses" value="0" style="width: 100%">

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