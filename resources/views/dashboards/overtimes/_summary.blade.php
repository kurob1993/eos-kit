<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan lembur {{$summary->attendanceQuota->duration}} menit</p>
<p><small>{{$summary->attendanceQuota->employee->personnelNoWithName}}</small></p>
<span class="label label-default">
    {{$summary->attendanceQuota->stage->description}}
</span>