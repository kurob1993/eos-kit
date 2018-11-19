<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan cuti {{$summary->absence->duration}} hari</p>
<p><small>{{$summary->absence->employee->personnelNoWithName}}</small></p>

@component('components._stage-description', [
    'class' => $summary->absence->stage->class_description, 
    'description' => $summary->absence->stage->description
])
@endcomponent