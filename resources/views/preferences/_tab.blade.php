<ul class="nav nav-tabs nav-tabs-primary nav-justified nav-justified-mobile bg-primary">
    <li @if(Request::segment(1) == 'preference')
            class="active"
        @endif>
        <a href="{{ route('preference.create') }}"> Preferences</a>
    </li>
    <li @if(Request::segment(1) == 'dislike') 
            class="active"
        @endif>
        <a href="{{ route('dislike.create') }}"> Dislikes </a>
    </li>
</ul>