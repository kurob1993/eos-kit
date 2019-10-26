<p class="pull-right" style="text-align:right;">
    {{$when}}<br>
    created by <br> {{$summary->CreatedBy}}
</p>
<p>{{$summary->employee->PersonnelNoWithName}}</p>
<p>{{$summary->perilaku}} ({{$summary->month}}-{{$summary->year}})</p>
@component('components._stage-description', [
    'class' => $summary->stage->class_description, 
    'description' => $summary->stage->description
])
@endcomponent