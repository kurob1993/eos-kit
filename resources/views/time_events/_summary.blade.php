<p class="pull-right">{{$when}}</p>
<p class="m-b-0">{{$summary->timeEventType->description}}</p>
<p><small>{{$summary->check_date->format('d.m.Y')}}-{{$summary->check_time}}</small></p>
@component('components._stage-description', [
    'class' => $summary->stage->class_description, 
    'description' => $summary->stage->description
])
@endcomponent