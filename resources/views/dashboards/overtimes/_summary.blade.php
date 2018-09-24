<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan lembur {{$summary->attendanceQuota->duration}} hari</p>
<p><small>{{$summary->attendanceQuota->user->personnelNoWithName}}</small></p>
<span class="label label-default">
    {{$summary->attendanceQuota->stage->description}}
</span>