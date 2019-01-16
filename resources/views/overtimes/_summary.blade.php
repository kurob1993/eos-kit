<p class="pull-right">{{$when}}</p>
<p>{{$summary->employee->PersonnelNoWithName}}</p>
<p>{{$summary->attendanceQuotaType->text}} ({{$summary->duration}} menit)</p>
@component('components._stage-description', [
    'class' => $summary->stage->class_description, 
    'description' => $summary->stage->description
])
@endcomponent