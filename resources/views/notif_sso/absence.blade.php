@extends('layouts.app') 

@section('content')
<!-- begin #page-container -->
<div class="col-sm-12" style="margin-top: 20px">
  <div class="panel panel-prussian" id="mydiv">
    <div class="panel-heading">
      <h4 class="panel-title">ABSENCE</h4>
    </div>
    <div class="panel-body">
      <div class="text-center">
          @if($absence->IsSentToSap)
            <i class="fa fa-check-circle fa-5x text-success" aria-hidden="true"></i>
            <p>Selamat {{$absenceType}} Anda Berhasil disetujui.</p>
          @endif

          @if($absence->IsDenied)
            <i class="fa fa-times-circle fa-5x text-danger" aria-hidden="true"></i>
            <p>Mohon Maaf {{$absenceType}} Anda ditolak.</p>
          @endif

          @if($absence->IsWaitingApproval)
          <i class="fa fa-check-circle fa-5x text-default" aria-hidden="true"></i>
            <p>Selamat {{$absenceType}} Anda Berhasil dibuat.</p>
          @endif
      </div>
      <table class="table table-striped table-bordered">
        <tbody>
          <tr>
            <td>ID</td>
            <td>{{ $absence->plain_id }}</td>
          </tr>
          <tr>
            <td>NIK</td>
            <td>{{ $absence->personnel_no }}</td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>{{ $absence->employee->name }}</td>
          </tr>
          <tr>
            <td>Mulai</td>
            <td>{{$absence->formatted_start_date}}</td>
          </tr>
          <tr>
            <td>Berakhir</td>
            <td>{{$absence->formatted_end_date}}</td>
          </tr>
          <tr>
            <td>Durasi</td>
            <td>{{ $absence->duration . ' hari' }}</td>
          </tr>
          <tr>
            <td>Keterangan</td>
            <td>{{ $absence->note }}</td>
          </tr>
          <tr>
            <td>Atasan</td>
            <td>{{ $absence->absenceApprovals()->first()->employee->name }}</td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</div>
<!-- end page container -->
@endsection

@push('styles')
<style>
 
</style>

<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush