<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan cuti {{$summary->absence->duration}} hari</p>
<p><small>{{$summary->absence->employee->personnelNoWithName}}</small></p>
<span class="label label-default">
    {{$summary->absence->stage->description}}
</span>