	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">
		<!-- begin #header -->
		@include('layouts._header')
		<!-- end #header -->

		<!-- begin #sidebar -->
		@include('layouts.employee._sidebar')
		<!-- end #sidebar -->

		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			@include('layouts._breadcrumb')
			<!-- end breadcrumb -->

			<!-- begin page-header -->
			@include('layouts._page-header')
			<!-- end page-header -->

			{{ $slot }}
			
		</div>
		<!-- end #content -->

		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top">
					<i class="fa fa-angle-up"></i>
				</a>
		<!-- end scroll to top btn -->
	</div>