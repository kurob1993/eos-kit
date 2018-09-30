@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Bantuan'])
<!-- begin panel -->
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Bantuan</h4>
    </div>
    <div class="panel-body">
        <form action="http://seantheme.com/" method="POST">
            <div id="wizard">
                <ol>
                    <li>
                        Identification 
                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</small>
                    </li>
                    <li>
                        Contact Information
                        <small>Aliquam bibendum felis id purus ullamcorper, quis luctus leo sollicitudin.</small>
                    </li>
                    <li>
                        Login
                        <small>Phasellus lacinia placerat neque pretium condimentum.</small>
                    </li>
                    <li>
                        Completed
                        <small>Sed nunc neque, dapibus non leo sed, rhoncus dignissim elit.</small>
                    </li>
                </ol>
                <!-- begin wizard step-1 -->
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Identification</legend>
                        <!-- begin row -->
                        <div class="row">
                            <!-- begin col-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" placeholder="John" class="form-control" />
                                </div>
                            </div>
                            <!-- end col-4 -->
                            <!-- begin col-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Middle Initial</label>
                                    <input type="text" name="middle" placeholder="A" class="form-control" />
                                </div>
                            </div>
                            <!-- end col-4 -->
                            <!-- begin col-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" placeholder="Smith" class="form-control" />
                                </div>
                            </div>
                            <!-- end col-4 -->
                        </div>
                        <!-- end row -->
                    </fieldset>
                </div>
                <!-- end wizard step-1 -->
                <!-- begin wizard step-2 -->
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Contact Information</legend>
                        <!-- begin row -->
                        <div class="row">
                            <!-- begin col-6 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" placeholder="123-456-7890" class="form-control" />
                                </div>
                            </div>
                            <!-- end col-6 -->
                            <!-- begin col-6 -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="text" name="email" placeholder="someone@example.com" class="form-control" />
                                </div>
                            </div>
                            <!-- end col-6 -->
                        </div>
                        <!-- end row -->
                    </fieldset>
                </div>
                <!-- end wizard step-2 -->
                <!-- begin wizard step-3 -->
                <div>
                    <fieldset>
                        <legend class="pull-left width-full">Login</legend>
                        <!-- begin row -->
                        <div class="row">
                            <!-- begin col-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Username</label>
                                    <div class="controls">
                                        <input type="text" name="username" placeholder="johnsmithy" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <!-- end col-4 -->
                            <!-- begin col-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pasword</label>
                                    <div class="controls">
                                        <input type="password" name="password" placeholder="Your password" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <!-- end col-4 -->
                            <!-- begin col-4 -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Confirm Pasword</label>
                                    <div class="controls">
                                        <input type="password" name="password2" placeholder="Confirmed password" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <!-- end col-6 -->
                        </div>
                        <!-- end row -->
                    </fieldset>
                </div>
                <!-- end wizard step-3 -->
                <!-- begin wizard step-4 -->
                <div>
                    <div class="jumbotron m-b-0 text-center">
                        <h1>Login Successfully</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consequat commodo porttitor. Vivamus eleifend, arcu in tincidunt semper, lorem odio molestie lacus, sed malesuada est lacus ac ligula. Aliquam bibendum felis id purus ullamcorper, quis luctus leo sollicitudin. </p>
                        <p><a class="btn btn-success btn-lg" role="button">Proceed to User Profile</a></p>
                    </div>
                </div>
                <!-- end wizard step-4 -->
            </div>
        </form>
    </div>
</div>
<!-- end panel -->
@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- VideoJS -->    
<link href={{ url("/plugins/videojs/video-js.min.css") }} rel="stylesheet">
<link href={{ url("/plugins/bootstrap-wizard/css/bwizard.min.css") }} rel="stylesheet">
<script src={{ url("/plugins/videojs/videojs-ie8.min.js") }}></script>
@endpush

@push('plugin-scripts')
<!-- VideoJS -->
<script src={{ url("/plugins/videojs/video.min.js") }}></script>
<script src={{ url("plugins/bootstrap-wizard/js/bwizard.js") }}></script>
<script src={{ url("plugins/bootstrap-wizard/form-wizards.demo.min.js") }}></script>
@endpush

@push('on-ready-scripts') 
FormWizard.init();
@endpush