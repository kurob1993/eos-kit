<p class="pull-right">{{$when}}</p>
<p class="m-b-0">{{$summary->timeEventType->description}}</p>
<p><small>{{$summary->check_date->format('d.m.Y')}}-{{$summary->check_time}}</small></p>
<span class="label label-default">
    {{$summary->stage->description}}
</span>