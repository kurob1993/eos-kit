@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container')
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Employee Dashboard</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">

  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection
