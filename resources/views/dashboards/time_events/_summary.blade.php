<p class="pull-right">{{$when}}</p>
<p class="m-b-0">Persetujuan tidak slash</p>
<p><small>{{$summary->timeEvent->employee->personnelNoWithName}}</small></p>

@component('components._stage-description', [
    'class' => $summary->timeEvent->stage->class_description, 
    'description' => $summary->timeEvent->stage->description
])
@endcomponent