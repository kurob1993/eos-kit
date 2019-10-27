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
                <td>Perilaku</td>
                <td>{{$ski->perilaku}}</td>
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
            <tbody>
              @foreach ($ski->skiDetail as $key => $item)
              <tr>
                <td class="text-center">{{$key+1}}</td>
                <td><input type="text" name="klp[{{$item->id}}]" value="{{$item->klp}}" style="width: 100%"></td>
                <td><input type="text" name="sasaran[{{$item->id}}]" value="{{$item->sasaran}}" style="width: 100%"></td>
                <td><input type="text" name="kode[{{$item->id}}]" value="{{$item->kode}}" style="width: 100%"></td>
                <td><input type="text" name="ukuran[{{$item->id}}]" value="{{$item->ukuran}}" style="width: 100%"></td>
                <td><input type="text" name="bobot[{{$item->id}}]" value="{{$item->bobot}}" style="width: 100%"></td>
                <td><input type="text" name="skor[{{$item->id}}]" value="{{$item->skor}}" style="width: 100%"></td>
                <td><input type="text" name="nilai[{{$item->id}}]" value="{{$item->nilai}}" style="width: 100%"></td>
              </tr>
              @endforeach
              @for ($i = 0; $i < 15-($key+1); $i++)
              <tr>
                  <td class="text-center">{{$key+$i+2}}</td>
                  <td><input type="text" name="add_klp[]" style="width: 100%"></td>
                  <td><input type="text" name="add_sasaran[]" style="width: 100%"></td>
                  <td><input type="text" name="add_kode[]" style="width: 100%"></td>
                  <td><input type="text" name="add_ukuran[]" style="width: 100%"></td>
                  <td><input type="text" name="add_bobot[]" style="width: 100%"></td>
                  <td><input type="text" name="add_skor[]" tyle="width: 100%"></td>
                  <td><input type="text" name="add_nilai[]" style="width: 100%"></td>
                </tr>
              @endfor
            </tbody>
          </table>
          <button class="btn btn-primary m-t-10" type="submit">
            <i class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </button>
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

@endpush

@push('on-ready-scripts')

@endpush