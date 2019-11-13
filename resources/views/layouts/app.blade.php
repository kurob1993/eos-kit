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
	<link href={{ url('/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') }} rel="stylesheet" />
	<!-- Bootstrap 3.3.4 -->
	<link href={{ url("/plugins/bootstrap/css/bootstrap.min.css") }} rel="stylesheet" />
	<link href={{ url("/plugins/bootstrap/css/BootstrapXL.css") }} rel="stylesheet" />
	<!-- FontAwesome -->
	<link href={{ url("/plugins/font-awesome/css/font-awesome.min.css") }} rel="stylesheet" />
	<!-- Simple line icons -->
	<link href={{ url("/plugins/simple-line-icons/simple-line-icons.css") }} rel="stylesheet">
	<link rel=”stylesheet” href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
	
	@stack('styles')
	
	<!-- Base Styles -->
	<link href={{ url("/css/animate.min.css") }} rel="stylesheet" />
	<link href={{ url("/css/style.css") }} rel="stylesheet" />
	<link href={{ url("/css/style-responsive.css") }} rel="stylesheet" />
	<link href={{ url("/css/theme/default.css") }} rel="stylesheet" id="theme" />
	<link href={{ url("/css/style-custom.css") }} rel="stylesheet" /> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
	
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
	<script src={{ url("/plugins/jquery/jquery-1.9.1.min.js") }}></script>
	<script src={{ url("/plugins/jquery/jquery-migrate-1.1.0.min.js") }}></script>
	<script src={{ url("/plugins/jquery-ui/ui/minified/jquery-ui.min.js") }}></script>
	<!-- Bootstrap -->
	<script src={{ url("/plugins/bootstrap/js/bootstrap.min.js") }}></script>
	<!-- slimscroll -->
	<script src={{ url("/plugins/slimscroll/jquery.slimscroll.min.js") }}></script>
	<!-- cookie -->
	<script src={{ url("/plugins/jquery-cookie/jquery.cookie.js") }}></script>

	@stack('plugin-scripts')
	@stack('custom-scripts')
	
	<!-- Base JS -->
	<script src={{ url("/js/apps.js") }}></script>

	<script>
		$(document).ready(function() {
			App.init();
			@stack('on-ready-scripts')
		});
	</script>

</body>

</html>