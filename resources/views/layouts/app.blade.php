<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name') }}</title>

	<!-- jQueryUI -->
	<link href={{ config('app.url').'/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css' }} rel="stylesheet" />
	<!-- Bootstrap 3.3.4 -->
	<link href={{ config('app.url').("/plugins/bootstrap/css/bootstrap.min.css") }} rel="stylesheet" />
	<link href={{ config('app.url').("/plugins/bootstrap/css/BootstrapXL.css") }} rel="stylesheet" />
	<!-- FontAwesome -->
	<link href={{ config('app.url').("/plugins/font-awesome/css/font-awesome.min.css") }} rel="stylesheet" />
	<!-- Simple line icons -->
	<link href={{ config('app.url').("/plugins/simple-line-icons/simple-line-icons.css") }} rel="stylesheet">
	
	@stack('styles')
	
	<!-- Base Styles -->
	<link href={{ config('app.url').("/css/animate.min.css") }} rel="stylesheet" />
	<link href={{ config('app.url').("/css/style.css") }} rel="stylesheet" />
	<link href={{ config('app.url').("/css/style-responsive.css") }} rel="stylesheet" />
	<link href={{ config('app.url').("/css/theme/default.css") }} rel="stylesheet" id="theme" />
	<link href={{ config('app.url').("/css/style-custom.css") }} rel="stylesheet" /> 
	
	<script>
		window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
	</script>
</head>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in">
		<span class="spinner"></span>
	</div>
	<!-- end #page-loader -->

	@yield('content')

	<!-- jQuery -->
	<script src={{ config('app.url').("/plugins/jquery/jquery-1.9.1.min.js") }}></script>
	<script src={{ config('app.url').("/plugins/jquery/jquery-migrate-1.1.0.min.js") }}></script>
	<script src={{ config('app.url').("/plugins/jquery-ui/ui/minified/jquery-ui.min.js") }}></script>
	<!-- Bootstrap -->
	<script src={{ config('app.url').("/plugins/bootstrap/js/bootstrap.min.js") }}></script>
	<!-- slimscroll -->
	<script src={{ config('app.url').("/plugins/slimscroll/jquery.slimscroll.min.js") }}></script>
	<!-- cookie -->
	<script src={{ config('app.url').("/plugins/jquery-cookie/jquery.cookie.js") }}></script>

	@stack('plugin-scripts')
	@stack('custom-scripts')
	
	<!-- Base JS -->
	<script src={{ config('app.url').("/js/apps.js") }}></script>

	<script>
		$(document).ready(function() {
			App.init();
			@stack('on-ready-scripts')
		});
	</script>

</body>

</html>