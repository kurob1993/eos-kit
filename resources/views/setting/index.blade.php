@extends('layouts.app') 
@section('content')
<!-- begin #page-container -->
@component('layouts.basis._page-container')
<div class="panel panel-prussian">
    <div class="panel-heading">
        <h4 class="panel-title">Panel Title here</h4>
    </div>
    @include('layouts._flash')
    <div class="panel-body">
        <div class="col-md-8 col-md-offset-2">

            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif

            <form method="post" action="{{ route('settings.store') }}" class="form-horizontal" role="form">
                {!! csrf_field() !!} @if(count(config('setting_fields', [])) ) @foreach(config('setting_fields') as $section => $fields)
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="{{ array_get($fields, 'icon', 'glyphicon glyphicon-flash') }}"></i> {{ $fields['title']
                        }}
                    </div>

                    <div class="panel-body">
                        <p class="text-muted">{{ $fields['desc'] }}</p>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7  col-md-offset-2">
                                @foreach($fields['elements'] as $field)
    @includeIf('setting.fields.' . $field['type'] ) @endforeach
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end panel for {{ $fields['title'] }} -->
                @endforeach @endif

                <div class="row m-b-md">
                    <div class="col-md-12">
                        <button class="btn-primary btn">
                                Save Settings
                            </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endcomponent
<!-- end page container -->
@endsection
 @push('styles')
<!-- DataTables -->
<link href="/plugins/DataTables/css/data-table.css" rel="stylesheet" />
<!-- Selectize -->
<link href="/plugins/selectize/selectize.css" rel="stylesheet">
<link href="/plugins/selectize/selectize.bootstrap3.css" rel="stylesheet">
<!-- Pace -->
<script src="/plugins/pace/pace.min.js"></script>

@endpush @push('plugin-scripts')
<!-- Selectize -->
<script src="/plugins/selectize/selectize.min.js"></script>
<!-- DataTables -->
<script src="/plugins/DataTables/js/jquery.dataTables.js"></script>
@endpush