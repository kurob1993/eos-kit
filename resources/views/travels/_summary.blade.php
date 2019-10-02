<p class="pull-right">{{$when}}</p>
<p>{{$summary->employee->PersonnelNoWithName}} <strong>({{$summary->duration}} hari)</strong> </p>
<p>{{$summary->start_date->format('d.m.Y')}} - {{$summary->end_date->format('d.m.Y')}}</p>
@component('components._stage-description', [
    'class' => $summary->stage->class_description, 
    'description' => $summary->stage->description
])
@endcomponent