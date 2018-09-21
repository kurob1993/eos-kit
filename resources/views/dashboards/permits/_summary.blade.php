<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan izin {{$summary->permit->duration}} hari</p>
<p><small>{{$summary->permit->user->personnelNoWithName}}</small></p>
<span class="label label-default">
    {{$summary->permit->stage->description}}
</span>