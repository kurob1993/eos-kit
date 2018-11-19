<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan izin {{$summary->permit->duration}} hari</p>
<p><small>{{$summary->permit->employee->personnelNoWithName}}</small></p>

@component('components._stage-description', [
    'class' => $summary->permit->stage->class_description, 
    'description' => $summary->permit->stage->description
])
@endcomponent