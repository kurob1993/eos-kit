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
          @if($absenceApproval->absence->IsSentToSap)
            <i class="fa fa-check-circle fa-5x text-success" aria-hidden="true"></i>
          @endif

          @if($absenceApproval->absence->IsDenied)
            <i class="fa fa-times-circle fa-5x text-danger" aria-hidden="true"></i>
          @endif

          @if($absenceApproval->absence->IsWaitingApproval)
            <i class="fa fa-check-circle fa-5x text-default" aria-hidden="true"></i>
          @endif

          <p>Pengajuan {{$absenceType}} ( {{ $absenceApproval->status()->first()->description}} )</p>

          @if($absenceApproval->status_id == '1')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#terima">Setujui</button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Tolak</button>
          @endif
      </div>
      <div class="m-t-10"></div>
      <table class="table table-striped table-bordered">
        <tbody>
          <tr>
            <td>ID</td>
            <td>{{$absence->plain_id}}</td>
          </tr>
          <tr>
            <td>NIK</td>
            <td>{{$absence->personnel_no}}</td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>{{$absence->employee->name}}</td>
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

<!-- Modal -->
<div id="terima" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yakin Menyetujui {{$absenceType}} ?</h4>
      </div>
      <form  style="display: inline" action="{{route('mobile.approve')}}" method="POST">
        <div class="modal-footer">
            {{ csrf_field() }}
            @if($absenceApproval->absence->is_a_leave)
              <input type="hidden" name="approval" value="leave">
            @else
              <input type="hidden" name="approval" value="absence">
            @endif
            <input type="hidden" name="id" value="{{$absenceApproval->id}}">
            <input type="submit" class="btn btn-primary" value="Ya">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="tolak" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yakin Menolak {{$absenceType}} ?</h4>
      </div>
      <form style="display: inline" action="{{route('mobile.reject')}}" method="POST">
        <div class="modal-footer">
            {{ csrf_field() }}
            @if($absenceApproval->absence->is_a_leave)
              <input type="hidden" name="approval" value="leave">
            @else
              <input type="hidden" name="approval" value="absence">
            @endif
            <input type="hidden" name="id" value="{{$absenceApproval->id}}">

          <input type="submit" class="btn btn-primary" value="Ya">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
        </div>
      </form>
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