<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan lembur {{$summary->attendanceQuota->duration}} menit</p>
<p><small>{{$summary->attendanceQuota->employee->personnelNoWithName}}</small></p>

@component('components._stage-description', [
    'class' => $summary->attendanceQuota->stage->class_description, 
    'description' => $summary->attendanceQuota->stage->description
])
@endcomponent