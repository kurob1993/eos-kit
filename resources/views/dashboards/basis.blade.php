@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.basis._page-container', ['page_header' => 'Basis Dashboard'])
<div class="panel panel-prussian">
  <div class="panel-heading">
    <h4 class="panel-title">Basis Dashboard</h4>
  </div>
  @include('layouts._flash')
  <div class="panel-body">

  </div>
</div>
@endcomponent
<!-- end page container -->
@endsection
