<p class="pull-right">{{$when}}</p>
<p class="m-b-0">{{$summary->permitType->text}} ({{$summary->duration}} hari)</p>
<p><small>{{$summary->start_date->format('d.m.Y')}}-{{$summary->end_date->format('d.m.Y')}}</small></p>
@component('components._stage-description', [
    'class' => $summary->stage->class_description, 
    'description' => $summary->stage->description
])
@endcomponent