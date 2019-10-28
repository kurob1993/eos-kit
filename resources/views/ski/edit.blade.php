@extends('layouts.app')

@section('content')

<!-- begin #page-container -->
@component($pageContainer, ['page_header' => 'Sasaran Kerja Karyawan'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Edit Sasaran Kerja Karyawan ID: {{ $ski->plain_id}}</h4>
      </div>
      <div class="panel-body">
        <form action="{{route('ski.update',$ski->id)}}" method="post">
          {{ csrf_field() }} {{ method_field('PUT') }}
        
          <table id="table-detail" class="table table-bordered table-condensed m-b-0" data-id="{{ $ski->plain_id }}">
            <tbody>
              <tr>
                <td>Nama</td>
                <td>{{ $ski->employee->PersonnelNoWithName }}</td>
              </tr>
              <tr>
                <td>Periode</td>
                <td>{{$ski->month}}-{{$ski->year}}</td>
              </tr>
              <tr>
                <td>Atasan</td>
                <td>
                  @foreach ($ski->skiApproval as $item)
                  @component('layouts._personnel-no-with-name', [
                  'personnel_no' => $item->employee->personnel_no,
                  'employee_name' => $item->employee->name])
                  @endcomponent
                  <br />
                  @endforeach
                </td>
              </tr>
              <tr>
                <td>Tahap</td>
                <td><span class="label label-default">{{ $ski->stage->description }}</span></td>
              </tr>
            </tbody>
          </table>

          <table class="table-bordered m-t-15" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center" style="width: 5%">NO</th>
                <th class="text-center" style="width: 10%">KLP</th>
                <th class="text-center" style="width: 30%">Sasaran Kerja</th>
                <th class="text-center" style="width: 10%">Kode</th>
                <th class="text-center" style="width: 25%">Ukuran Prestasi Kerja</th>
                <th class="text-center" style="width: 6%">Bobot</th>
                <th class="text-center" style="width: 6%">Skor</th>
                <th class="text-center" style="width: 8%">Nilai</th>
              </tr>
            </thead>
            <tbody id="tbody">
              @foreach ($ski->skiDetail as $key => $item)
              <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>
                  <select name="klp[{{$item->id}}]" id="klp{{$key}}" style="width: 100%; height: 26px">
                    <option value=""></option>
                    <option value="Perilaku" {{$item->klp == 'Perilaku' ? 'selected':''}}>Perilaku</option>
                    <option value="Kinerja" {{$item->klp == 'Kinerja' ? 'selected':''}}>Kinerja</option>
                  </select>
                </td>
                <td><input type="text" name="sasaran[{{$item->id}}]" value="{{$item->sasaran}}" style="width: 100%"></td>
                <td><input type="text" name="kode[{{$item->id}}]" value="{{$item->kode}}" style="width: 100%"></td>
                <td><input type="text" name="ukuran[{{$item->id}}]" value="{{$item->ukuran}}" style="width: 100%"></td>
                <td>
                  <input type="text" 
                    name="bobot[{{$item->id}}]" 
                    value="{{$item->bobot}}" 
                    id="bobot{{$key}}"
                    style="width: 100%; text-align: right"
                    onkeyup="setNilai({{$key}})"
                  >
                </td>
                <td>
                  <input type="text" 
                    name="skor[{{$item->id}}]" 
                    value="{{$item->skor}}" 
                    id="skor{{$key}}" 
                    style="width: 100%; text-align: right"
                    onkeyup="setNilai({{$key}})"
                  >
                </td>
                <td>
                  <input type="text" 
                    name="nilai[{{$item->id}}]" 
                    value="{{$item->nilai}}" 
                    id="nilai{{$key}}"
                    style="width: 100%; text-align: right"
                    readonly
                  >
                </td>
              </tr>
              @endforeach
              @php( $key = isset($key) ? $key : -1 )
              @for ($i = 0; $i < 15-($key+1); $i++)
              <tr>
                  <td class="text-center">{{$key+$i+2}}</td>
                  <td>
                    <select name="add_klp[]" id="klp{{$key+$i+1}}" style="width: 100%; height: 26px">
                      <option value=""></option>
                      <option value="Perilaku">Perilaku</option>
                      <option value="Kinerja">Kinerja</option>
                    </select>
                  </td>
                  <td><input type="text" name="add_sasaran[]" style="width: 100%"></td>
                  <td><input type="text" name="add_kode[]" style="width: 100%"></td>
                  <td><input type="text" name="add_ukuran[]" style="width: 100%"></td>
                  <td>
                    <input type="text" 
                      name="add_bobot[]" 
                      id="bobot{{$key+$i+1}}"
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$key+$i+1}})"
                    >
                  </td>
                  <td>
                    <input type="text" 
                      name="add_skor[]" 
                      id="skor{{$key+$i+1}}"
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$key+$i+1}})"
                    >
                  </td>
                  <td>
                    <input type="text" 
                      name="add_nilai[]" 
                      id="nilai{{$key+$i+1}}"
                      style="width: 100%; text-align: right"
                      onkeyup="setNilai({{$key+$i+1}})"
                      readonly
                    >
                  </td>
                </tr>
              @endfor
            </tbody>
          </table>
          <input type="hidden" id="id" value="{{$key+$i+1}}">
          <input type="hidden" id="sum_perilaku">
          <input type="hidden" id="sum_kinerja">
          <button class="btn btn-primary m-t-10" type="submit" id="kirim">
            <i class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </button>
          <button type="button" 
            class="btn btn-warning m-t-10" 
            id="tambah_kolom"
            onclick="add_column()"
          >
            Tambah Kolom
          </button>
          <div class="pull-right">
              <span id="bobot_perilaku"></span>
              -
              <span id="bobot_kinerja"></span>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endcomponent
<!-- end page container -->

@endsection

@push('styles')
<!-- Datepicker -->
<link href={{ url("/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />
<!-- Selectize -->
<link href={{ url("/plugins/bootstrap-select/bootstrap-select.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
<!-- Parsley -->
<link href={{ url("/plugins/parsley/src/parsley.css") }} rel="stylesheet" />
<!-- Timepicker -->
<link href={{ url( "/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css") }} rel="stylesheet" />
<!-- Pace -->
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<!-- Selectize -->
<script src={{ url("/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- Parsley -->
<script src={{ url("/plugins/parsley/dist/parsley.js") }}></script>
<!-- Timepicker -->
<script src={{ url( "/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js") }}></script>
@endpush

@push('custom-scripts')
<script>
    function setNilai(id) {
      var count = Number( $('#id').val() );
      var bobot = $('#bobot'+id).val();
      var skor = $('#skor'+id).val();
      var sum_perilaku = 0;
      var sum_kinerja = 0;
      
      $('#nilai'+id).val(bobot*skor);
  
      for (let index = 0; index < count; index++) {
        var klp = $('#klp'+index).val();
        var bobot =  Number( $('#bobot'+index).val() );
        var skor =  Number( $('#skor'+index).val() );
        // console.log(klp);
        if(klp == 'Perilaku'){
          sum_perilaku += bobot;
          $('#sum_perilaku').val(sum_perilaku);
          
        }
  
        if(klp == 'Kinerja'){
          sum_kinerja += bobot;
          $('#sum_kinerja').val(sum_kinerja);
        }
        
      }
      var msg_perilaku = '';
      var msg_kinerja = '';
      if(sum_perilaku < 100 && sum_perilaku !== 0){
        msg_perilaku = 'Bobot Perilaku Kurang dari 100';
      }
      if(sum_perilaku > 100 && sum_perilaku !== 0){
        msg_perilaku = 'Bobot Perilaku Lebih dari 100';
      }
      if(sum_kinerja < 100 && sum_kinerja !== 0){
        msg_kinerja = 'Bobot Kinerja Kurang dari 100';
      }
      if(sum_kinerja > 100 && sum_kinerja !== 0){
        msg_kinerja = 'Bobot Kinerja Lebih dari 100';
      }
      $('#bobot_perilaku').html(msg_perilaku);
      $('#bobot_kinerja').html(msg_kinerja);
  
      if(sum_kinerja == 100 && sum_perilaku == 100){
        $('#kirim').removeClass('hidden');
      }else{
        $('#kirim').addClass('hidden');
      }
    }
    function add_column() {
      var id = Number( $('#id').val() );
      var kolom = '<tr>'+
        '<td class="text-center">'+ (id+1) +'</td>'+
        '<td>'+
          '<select name="klp[]" style="width: 100%; height: 26px">'+
            '<option value=""></option>'+
            '<option value="Perilaku">Perilaku</option>'+
            '<option value="Kinerja">Kinerja</option>'+
          '</select> '+
        '</td>'+
        '<td><input type="text" name="sasaran[]" style="width: 100%"></td>'+
        '<td><input type="text" name="kode[]" style="width: 100%"></td>'+
        '<td><input type="text" name="ukuran[]" style="width: 100%"></td>'+
        '<td> '+
          '<input type="text" '+
            'name="bobot[]" '+
            'id="bobot'+id+'" '+
            'style="width: 100%; text-align: right"'+
            'onkeyup="setNilai('+id+')"'+
          '> '+
        '</td>'+
        '<td> '+
          '<input type="text" '+
            'name="skor[]" '+
            'id="skor'+id+'" '+
            'style="width: 100%; text-align: right"'+
            'onkeyup="setNilai('+id+')"'+
          '>'+
        '</td>'+
        '<td>'+
          '<input type="text" '+
            'name="nilai[]" '+
            'id="nilai'+id+'" '+
            'style="width: 100%; text-align: right"'+
            'readonly'+
          '>'+
        '</td>'+
        '</tr>';
      $('#tbody').append(kolom);
      $('#id').val(id+1);
    }
  </script>
@endpush

@push('on-ready-scripts')

@endpush