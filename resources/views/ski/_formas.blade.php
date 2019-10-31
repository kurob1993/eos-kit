{{--
Form Elements: personnel_no, start_date, day_assignment, from,
to, overtime_reason, delegation (if have subordinates)
--}}

{{-- <div class="col-lg-4">
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
</div> --}}

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
        onclick="return ceking(1)">
        Input Perilaku
      </button>

      <button id="btn-sasaran"
        type="button" 
        class="btn btn-warning" 
        data-backdrop="static" 
        data-toggle="modal" 
        data-target="#myModal"
        style="display: none"
        onclick="return ceking(2)">
        Input Kinerja
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
          <table class="table-responsive" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center" style="width: 5%">NO</th>
                {{-- <th class="text-center" style="width: 10%">KLP</th> --}}
                <th class="text-center" style="width: 30%">Sasaran Kerja</th>
                <th class="text-center" style="width: 10%">Kode</th>
                <th class="text-center" style="width: 25%">Ukuran Prestasi Kerja</th>
                <th class="text-center" style="width: 6%">Bobot</th>
                <th class="text-center" style="width: 6%">Skor</th>
                <th class="text-center" style="width: 8%">Nilai</th>
              </tr>
            </thead>
            <tbody id="tbody">
              @php($u = 1)
              @foreach($perilakus as $perilaku) 
                @php($required = '')
                @php($required = 'required')
               
                <tr>
                  <td class="text-center">{{ $u++ }}</td>
                  {{-- <td>
                    <select {{ $required }} name="klp[]" id="klp{{$i}}" style="width: 100%; height: 26px">
                      <option value=""></option>
                      <option value="Perilaku">Perilaku</option>
                      <option value="Kinerja">Kinerja</option>
                    </select>
                  </td> --}}
                  <td>
                      <select {{ $required }} name="klpp[]" id="klpp{{ $perilaku->id }}" style="width: 100%; height: 26px; display:none">
                        <option value="Perilaku">Perilaku</option>
                      </select>
                      <input type="text" {{ $required }} name="sasaranp[]" style="width: 100%" value="{{ $perilaku->name }}">
                  </td>
                  <td><input type="text" name="kodep[]" style="width: 100%" value=""></td>
                  <td><input type="text" name="ukuranp[]" style="width: 100%"></td>
                  <td>
                    <input type="text" {{ $required }} 
                      name="bobotp[]" 
                      id="bobot{{$perilaku->id}}"
                      value="10"
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$perilaku->id}})"
                    >
                  </td>
                  <td>
                    <input type="text" 
                      name="skorp[]" 
                      id="skor{{$perilaku->id}}" 
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$perilaku->id}})"
                    >
                  </td>
                  <td>
                    <input type="text" 
                      name="nilaip[]" 
                      id="nilai{{$perilaku->id}}"
                      style="width: 100%; text-align: right"
                      readonly
                    >
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <input type="hidden" id="id" value="{{$perilaku->id}}">
          {{-- <input type="hidden" id="sum_perilaku"> --}}
          {{-- <input type="hidden" id="sum_kinerja"> --}}
        </div>
        <div class="modal-footer">
          <div class="pull-left">
              <span id="bobot_perilaku"></span>
              -
              <span id="bobot_kinerja"></span>
          </div>
          
          <button type="submit" 
            class="btn btn-primary" 
            id="kirimPerilaku"
          >
            Kirim
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 85%">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sasaran Kerja</h4>
        </div>
        <div class="modal-body">
          <table class="table-responsive" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center" style="width: 5%">NO</th>
                {{-- <th class="text-center" style="width: 10%">KLP</th> --}}
                <th class="text-center" style="width: 30%">Sasaran Kerja</th>
                <th class="text-center" style="width: 10%">Kode</th>
                <th class="text-center" style="width: 25%">Ukuran Prestasi Kerja</th>
                <th class="text-center" style="width: 6%">Bobot</th>
                <th class="text-center" style="width: 6%">Skor</th>
                <th class="text-center" style="width: 8%">Nilai</th>
              </tr>
            </thead>
            <tbody id="tbody">
              @for ($i = 0; $i < 15; $i++) 
                @php($required = '')
                @if ($i==0)
                @php($required = '')
                @endif
                <tr>
                  <td class="text-center">{{$i+1}}</td>
                  {{-- <td> --}}
                    <select {{ $required }} name="klp[]" id="klp{{$i}}" style="width: 100%; height: 26px; display:none">
                      <option value="Kinerja">Kinerja</option>
                    </select>
                  {{-- </td> --}}
                  <td><input type="text" {{ $required }} name="sasaran[]" style="width: 100%"></td>
                  <td><input type="text" name="kode[]" style="width: 100%"></td>
                  <td><input type="text" name="ukuran[]" style="width: 100%"></td>
                  <td>
                    <input type="text" {{ $required }} 
                      name="bobot[]" 
                      id="bobot{{$i}}"
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$i}})"
                    >
                  </td>
                  <td>
                    <input type="text" 
                      name="skor[]" 
                      id="skor{{$i}}" 
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$i}})"
                    >
                  </td>
                  <td>
                    <input type="text" 
                      name="nilai[]" 
                      id="nilai{{$i}}"
                      style="width: 100%; text-align: right"
                      readonly
                    >
                  </td>
                </tr>
              @endfor
            </tbody>
          </table>
          <input type="hidden" id="id" value="{{$i}}">
          <input type="hidden" id="sum_perilaku">
          <input type="hidden" id="sum_kinerja">
        </div>
        <div class="modal-footer">
          <div class="pull-left">
              <span id="bobot_perilaku"></span>
              -
              <span id="bobot_kinerja"></span>
          </div>
          
          <button type="submit" 
            class="btn btn-primary hidden" 
            id="kirim"
          >
            Kirim
          </button>

          <button type="button" 
            class="btn btn-warning" 
            id="tambah_kolom"
            onclick="add_column({{$i}})"
          >
            Tambah Kolom
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>