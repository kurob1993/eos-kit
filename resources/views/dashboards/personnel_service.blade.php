@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.personnel_service._page-container', ['page_header' => 'Personnel Service Dashboard'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Personnel Service Dashboard </h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">

  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection
