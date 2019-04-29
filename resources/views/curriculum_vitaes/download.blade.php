@extends('layouts.app')

@section('content')
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="index.html" class="navbar-brand"><span class="navbar-logo"></span> Color Admin</a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					<li>
						<form class="navbar-form full-width">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Enter keyword" />
								<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</li>
					<li class="dropdown">
						<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
							<i class="fa fa-bell-o"></i>
							<span class="label">5</span>
						</a>
						<ul class="dropdown-menu media-list pull-right animated fadeInDown">
                            <li class="dropdown-header">Notifications (5)</li>
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">Server Error Reports</h6>
                                        <div class="text-muted f-s-11">3 minutes ago</div>
                                    </div>
                                </a>
                            </li>
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><img src="assets/img/user-1.jpg" class="media-object" alt="" /></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">John Smith</h6>
                                        <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                        <div class="text-muted f-s-11">25 minutes ago</div>
                                    </div>
                                </a>
                            </li>
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><img src="assets/img/user-2.jpg" class="media-object" alt="" /></div>
                                    <div class="media-body">
                                        <h6 class="media-heading">Olivia</h6>
                                        <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                        <div class="text-muted f-s-11">35 minutes ago</div>
                                    </div>
                                </a>
                            </li>
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><i class="fa fa-plus media-object bg-green"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading"> New User Registered</h6>
                                        <div class="text-muted f-s-11">1 hour ago</div>
                                    </div>
                                </a>
                            </li>
                            <li class="media">
                                <a href="javascript:;">
                                    <div class="media-left"><i class="fa fa-envelope media-object bg-blue"></i></div>
                                    <div class="media-body">
                                        <h6 class="media-heading"> New Email From John</h6>
                                        <div class="text-muted f-s-11">2 hour ago</div>
                                    </div>
                                </a>
                            </li>
                            <li class="dropdown-footer text-center">
                                <a href="javascript:;">View more</a>
                            </li>
						</ul>
					</li>
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="assets/img/user-13.jpg" alt="" /> 
							<span class="hidden-xs">Adam Schwartz</span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li><a href="javascript:;">Edit Profile</a></li>
							<li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
							<li><a href="javascript:;">Calendar</a></li>
							<li><a href="javascript:;">Setting</a></li>
							<li class="divider"></li>
							<li><a href="javascript:;">Log Out</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<div class="image">
							<a href="javascript:;"><img src="assets/img/user-13.jpg" alt="" /></a>
						</div>
						<div class="info">
							Sean Ngu
							<small>Front end developer</small>
						</div>
					</li>
				</ul>
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->
				<ul class="nav">
					<li class="nav-header">Navigation</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-laptop"></i>
						    <span>Dashboard</span>
					    </a>
						<ul class="sub-menu">
						    <li><a href="index.html">Dashboard v1</a></li>
						    <li><a href="index_v2.html">Dashboard v2</a></li>
						</ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
							<span class="badge pull-right">10</span>
							<i class="fa fa-inbox"></i> 
							<span>Email</span>
						</a>
						<ul class="sub-menu">
						    <li><a href="email_inbox.html">Inbox v1</a></li>
						    <li><a href="email_inbox_v2.html">Inbox v2</a></li>
						    <li><a href="email_compose.html">Compose</a></li>
						    <li><a href="email_detail.html">Detail</a></li>
						</ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-suitcase"></i>
						    <span>UI Elements <span class="label label-theme m-l-5">NEW</span></span> 
						</a>
						<ul class="sub-menu">
							<li><a href="ui_general.html">General</a></li>
							<li><a href="ui_typography.html">Typography</a></li>
							<li><a href="ui_tabs_accordions.html">Tabs & Accordions</a></li>
							<li><a href="ui_unlimited_tabs.html">Unlimited Nav Tabs</a></li>
							<li><a href="ui_modal_notification.html">Modal & Notification</a></li>
							<li><a href="ui_widget_boxes.html">Widget Boxes</a></li>
							<li><a href="ui_media_object.html">Media Object</a></li>
							<li><a href="ui_buttons.html">Buttons</a></li>
							<li><a href="ui_icons.html">Icons</a></li>
							<li><a href="ui_simple_line_icons.html">Simple Line Icons</a></li>
							<li><a href="ui_ionicons.html">Ionicons</a></li>
							<li><a href="ui_tree.html">Tree View <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
							<li><a href="ui_language_bar_icon.html">Language Bar & Icon <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
						</ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-file-o"></i>
						    <span>Form Stuff <span class="label label-theme m-l-5">NEW</span></span> 
						</a>
						<ul class="sub-menu">
							<li><a href="form_elements.html">Form Elements</a></li>
							<li><a href="form_plugins.html">Form Plugins <i class="fa fa-paper-plane text-theme"></i></a></li>
							<li><a href="form_slider_switcher.html">Form Slider + Switcher</a></li>
							<li><a href="form_validation.html">Form Validation</a></li>
							<li><a href="form_wizards.html">Wizards</a></li>
							<li><a href="form_wizards_validation.html">Wizards + Validation</a></li>
							<li><a href="form_wysiwyg.html">WYSIWYG</a></li>
							<li><a href="form_editable.html">X-Editable</a></li>
							<li><a href="form_multiple_upload.html">Multiple File Upload</a></li>
						</ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-th"></i>
						    <span>Tables</span>
						</a>
						<ul class="sub-menu">
							<li><a href="table_basic.html">Basic Tables</a></li>
							<li class="has-sub">
							    <a href="javascript:;"><b class="caret pull-right"></b> Managed Tables</a>
							    <ul class="sub-menu">
							        <li><a href="table_manage.html">Default</a></li>
							        <li><a href="table_manage_autofill.html">Autofill</a></li>
							        <li><a href="table_manage_colreorder.html">ColReorder</a></li>
							        <li><a href="table_manage_colvis.html">ColVis</a></li>
							        <li><a href="table_manage_fixed_columns.html">Fixed Column</a></li>
							        <li><a href="table_manage_fixed_header.html">Fixed Header</a></li>
							        <li><a href="table_manage_keytable.html">KeyTable</a></li>
							        <li><a href="table_manage_responsive.html">Responsive</a></li>
							        <li><a href="table_manage_scroller.html">Scroller</a></li>
							        <li><a href="table_manage_tabletools.html">TableTools</a></li>
							        <li><a href="table_manage_combine.html">Extension Combination</a></li>
							    </ul>
							</li>
						</ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
							<i class="fa fa-star"></i> 
							<span>Front End</span>
						</a>
						<ul class="sub-menu">
						    <li><a href="http://seantheme.com/color-admin-v1.7/frontend/one-page-parallax/index.html" target="_blank">One Page Parallax</a></li>
						</ul>
					</li>
					<li class="has-sub">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-envelope"></i>
					        <span>Email Template</span>
					    </a>
						<ul class="sub-menu">
							<li><a href="email_system.html">System Template</a></li>
							<li><a href="email_newsletter.html">Newsletter Template</a></li>
						</ul>
					</li>
					<li class="has-sub">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-area-chart"></i>
						    <span>Chart</span>
						</a>
						<ul class="sub-menu">
						    <li><a href="chart-flot.html">Flot Chart</a></li>
						    <li><a href="chart-morris.html">Morris Chart</a></li>
							<li><a href="chart-js.html">Chart JS</a></li>
						</ul>
					</li>
					<li><a href="calendar.html"><i class="fa fa-calendar"></i> <span>Calendar</span></a></li>
					<li class="has-sub">
					    <a href="javascript:;">
					        <b class="caret pull-right"></b>
					        <i class="fa fa-map-marker"></i>
					        <span>Map</span>
					    </a>
						<ul class="sub-menu">
							<li><a href="map_vector.html">Vector Map</a></li>
							<li><a href="map_google.html">Google Map</a></li>
						</ul>
					</li>
					<li class="has-sub">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-camera"></i>
						    <span>Gallery</span>
						</a>
					    <ul class="sub-menu">
					        <li><a href="gallery.html">Gallery v1</a></li>
					        <li><a href="gallery_v2.html">Gallery v2</a></li>
					    </ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-cogs"></i>
						    <span>Page Options</span>
						</a>
						<ul class="sub-menu">
							<li class="active"><a href="page_blank.html">Blank Page</a></li>
							<li><a href="page_with_footer.html">Page with Footer</a></li>
							<li><a href="page_without_sidebar.html">Page without Sidebar</a></li>
							<li><a href="page_with_right_sidebar.html">Page with Right Sidebar</a></li>
							<li><a href="page_with_minified_sidebar.html">Page with Minified Sidebar</a></li>
							<li><a href="page_with_two_sidebar.html">Page with Two Sidebar</a></li>
							<li><a href="page_with_line_icons.html">Page with Line Icons</a></li>
							<li><a href="page_with_ionicons.html">Page with Ionicons</a></li>
							<li><a href="page_full_height.html">Full Height Content</a></li>
							<li><a href="page_with_wide_sidebar.html">Page with Wide Sidebar <i class="fa fa-paper-plane text-theme"></i></a></li>
							<li><a href="page_with_light_sidebar.html">Page with Light Sidebar <i class="fa fa-paper-plane text-theme"></i></a></li>
							<li><a href="page_with_mega_menu.html">Page with Mega Menu <i class="fa fa-paper-plane text-theme"></i></a></li>
						</ul>
					</li>
					<li class="has-sub active">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-gift"></i>
						    <span>Extra <span class="label label-theme m-l-5">NEW</span></span>
						</a>
						<ul class="sub-menu">
						    <li><a href="extra_timeline.html">Timeline</a></li>
						    <li><a href="extra_coming_soon.html">Coming Soon Page</a></li>
							<li><a href="extra_search_results.html">Search Results</a></li>
							<li><a href="extra_invoice.html">Invoice</a></li>
							<li><a href="extra_404_error.html">404 Error Page</a></li>
							<li class="active"><a href="extra_profile.html">Profile Page <i class="fa fa-paper-plane text-theme"></i></a></li>
						</ul>
					</li>
					<li class="has-sub">
					    <a href="javascript:;">
					        <b class="caret pull-right"></b>
					        <i class="fa fa-key"></i>
					        <span>Login & Register <span class="label label-theme m-l-5">NEW</span></span>
					    </a>
					    <ul class="sub-menu">
							<li><a href="login.html">Login</a></li>
					        <li><a href="login_v2.html">Login v2</a></li>
					        <li><a href="login_v3.html">Login v3 <i class="fa fa-paper-plane text-theme"></i></a></li>
					        <li><a href="register_v3.html">Register v3 <i class="fa fa-paper-plane text-theme"></i></a></li>
					    </ul>
					</li>
					<li class="has-sub">
					    <a href="javascript:;">
					        <b class="caret pull-right"></b>
					        <i class="fa fa-cubes"></i>
					        <span>Version</span>
					    </a>
					    <ul class="sub-menu">
							<li><a href="javascript:;">HTML</a></li>
					        <li><a href="http://seantheme.com/color-admin-v1.7/admin/ajax/index.html">AJAX</a></li>
					    </ul>
					</li>
					<li class="has-sub">
					    <a href="javascript:;">
					        <b class="caret pull-right"></b>
					        <i class="fa fa-medkit"></i>
					        <span>Helper</span>
					    </a>
					    <ul class="sub-menu">
							<li><a href="helper_css.html">Predefined CSS Classes</a></li>
					    </ul>
					</li>
					<li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-align-left"></i> 
						    <span>Menu Level</span>
						</a>
						<ul class="sub-menu">
							<li class="has-sub">
								<a href="javascript:;">
						            <b class="caret pull-right"></b>
						            Menu 1.1
						        </a>
								<ul class="sub-menu">
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    Menu 2.1
										</a>
										<ul class="sub-menu">
											<li><a href="javascript:;">Menu 3.1</a></li>
											<li><a href="javascript:;">Menu 3.2</a></li>
										</ul>
									</li>
									<li><a href="javascript:;">Menu 2.2</a></li>
									<li><a href="javascript:;">Menu 2.3</a></li>
								</ul>
							</li>
							<li><a href="javascript:;">Menu 1.2</a></li>
							<li><a href="javascript:;">Menu 1.3</a></li>
						</ul>
					</li>
			        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li><a href="javascript:;">Extra</a></li>
				<li class="active">Profile Page</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Profile Page <small>header small text goes here...</small></h1>
			<!-- end page-header -->
			<!-- begin profile-container -->
            <div class="profile-container">
                <!-- begin profile-section -->
                <div class="profile-section">
                    <!-- begin profile-left -->
                    <div class="profile-left">
                        <!-- begin profile-image -->
                        <div class="profile-image">
                            <img src="assets/img/profile-cover.jpg" />
                            <i class="fa fa-user hide"></i>
                        </div>
                        <!-- end profile-image -->
                        <div class="m-b-10">
                            <a href="#" class="btn btn-warning btn-block btn-sm">Change Picture</a>
                        </div>
                        <!-- begin profile-highlight -->
                        <div class="profile-highlight">
                            <h4><i class="fa fa-cog"></i> Only My Contacts</h4>
                            <div class="checkbox m-b-5 m-t-0">
                                <label><input type="checkbox" /> Show my timezone</label>
                            </div>
                            <div class="checkbox m-b-0">
                                <label><input type="checkbox" /> Show i have 14 contacts</label>
                            </div>
                        </div>
                        <!-- end profile-highlight -->
                    </div>
                    <!-- end profile-left -->
                    <!-- begin profile-right -->
                    <div class="profile-right">
                        <!-- begin profile-info -->
                        <div class="profile-info">
                            <!-- begin table -->
                            <div class="table-responsive">
                                <table class="table table-profile">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <h4>Micheal	Meyer <small>Lorraine Stokes</small></h4>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="highlight">
                                            <td class="field">Mood</td>
                                            <td><a href="#">Add Mood Message</a></td>
                                        </tr>
                                        <tr class="divider">
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Mobile</td>
                                            <td><i class="fa fa-mobile fa-lg m-r-5"></i> +1-(847)- 367-8924 <a href="#" class="m-l-5">Edit</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Home</td>
                                            <td><a href="#">Add Number</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Office</td>
                                            <td><a href="#">Add Number</a></td>
                                        </tr>
                                        <tr class="divider">
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr class="highlight">
                                            <td class="field">About Me</td>
                                            <td><a href="#">Add Description</a></td>
                                        </tr>
                                        <tr class="divider">
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Country/Region</td>
                                            <td>
                                                <select class="form-control input-inline input-xs" name="region">
                                                    <option value="US" selected>United State</option>
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="field">City</td>
                                            <td>Los Angeles</td>
                                        </tr>
                                        <tr>
                                            <td class="field">State</td>
                                            <td><a href="#">Add State</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Website</td>
                                            <td><a href="#">Add Webpage</a></td>
                                        </tr>
                                        <tr>
                                            <td class="field">Gender</td>
                                            <td>
                                                <select class="form-control input-inline input-xs" name="gender">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Birthdate</td>
                                            <td>
                                                <select class="form-control input-inline input-xs" name="day">
                                                    <option value="04" selected>04</option>
                                                </select>
                                                -
                                                <select class="form-control input-inline input-xs" name="month">
                                                    <option value="11" selected>11</option>
                                                </select>
                                                -
                                                <select class="form-control input-inline input-xs" name="year">
                                                    <option value="1989" selected>1989</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="field">Language</td>
                                            <td>
                                                <select class="form-control input-inline input-xs" name="language">
                                                    <option value="" selected>English</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table -->
                        </div>
                        <!-- end profile-info -->
                    </div>
                    <!-- end profile-right -->
                </div>
                <!-- end profile-section -->
                <!-- begin profile-section -->
                <div class="profile-section">
                    <!-- begin row -->
                    <div class="row">
                        <!-- begin col-4 -->
                        <div class="col-md-4">
                            <h4 class="title">Message <small>56 messages</small></h4>
                            <!-- begin scrollbar -->
                            <div data-scrollbar="true" data-height="280px" class="bg-silver">
                                <!-- begin chats -->
                                <ul class="chats">
                                    <li class="left">
                                        <span class="date-time">yesterday 11:23pm</span>
                                        <a href="javascript:;" class="name">Sowse Bawdy</a>
                                        <a href="javascript:;" class="image"><img alt="" src="assets/img/user-12.jpg"></a>
                                        <div class="message">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit volutpat. Praesent mattis interdum arcu eu feugiat.
                                        </div>
                                    </li>
                                    <li class="right">
                                        <span class="date-time">08:12am</span>
                                        <a href="#" class="name"><span class="label label-primary">ADMIN</span> Me</a>
                                        <a href="javascript:;" class="image"><img alt="" src="assets/img/user-13.jpg"></a>
                                        <div class="message">
                                            Nullam posuere, nisl a varius rhoncus, risus tellus hendrerit neque.
                                        </div>
                                    </li>
                                    <li class="left">
                                        <span class="date-time">09:20am</span>
                                        <a href="#" class="name">Neck Jolly</a>
                                        <a href="javascript:;" class="image"><img alt="" src="assets/img/user-10.jpg"></a>
                                        <div class="message">
                                            Euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                                        </div>
                                    </li>
                                    <li class="left">
                                        <span class="date-time">11:15am</span>
                                        <a href="#" class="name">Shag Strap</a>
                                        <a href="javascript:;" class="image"><img alt="" src="assets/img/user-14.jpg"></a>
                                        <div class="message">
                                            Nullam iaculis pharetra pharetra. Proin sodales tristique sapien mattis placerat.
                                        </div>
                                    </li>
                                </ul>
                                <!-- end chats -->
                            </div>
                            <!-- end scrollbar -->
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4">
                            <h4 class="title">Sales <small>3 sales</small></h4>
                            <!-- begin scrollbar -->
                            <div data-scrollbar="true" data-height="280px" class="bg-silver">
                                <!-- begin table -->
                                <table class="table table-condensed">
									<thead>
										<tr>
											<th></th>
											<th>Product</th>
											<th>Amount</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										    <td class="col-md-1 p-r-5">
												<a href="javascript:;" class="pull-left">
													<img src="assets/img/product/product-1.png" width="40px" alt="">
												</a>
											</td>
											<td>
												<h5 class="m-t-0 m-b-5">iPad Air 3</h5>
											</td>
											<td>$349.00</td>
											<td>13/02/2013</td>
										</tr>
										<tr>
										    <td class="col-md-1 p-r-5">
												<a href="javascript:;" class="pull-left">
													<img src="assets/img/product/product-2.png" width="40px" alt="">
												</a>
											</td>
											<td>
												<h5 class="m-t-0 m-b-5">Iphone6</h5>
											</td>
											<td>$399.00</td>
											<td>13/02/2013</td>
										</tr>
										<tr>
										    <td class="col-md-1 p-r-5">
												<a href="javascript:;" class="pull-left">
													<img src="assets/img/product/product-3.png" width="40px" alt="">
												</a>
											</td>
											<td>
												<h5 class="m-t-0 m-b-5">Macbook Pro</h5>
											</td>
											<td>$499.00</td>
											<td>13/02/2013</td>
										</tr>
										<tr>
										    <td class="col-md-1 p-r-5">
												<a href="javascript:;">
													<img src="assets/img/product/product-4.png" width="40px" alt="">
												</a>
											</td>
											<td>
												<h5 class="m-t-0 m-b-5">Samsung Galaxy s4</h5>
											</td>
											<td>$230.00</td>
											<td>13/02/2013</td>
										</tr>
										<tr>
										    <td class="col-md-1 p-r-5">
												<a href="javascript:;" class="pull-left">
													<img src="assets/img/product/product-5.png" width="40px" alt="">
												</a>
											</td>
											<td>
												<h5 class="m-t-0 m-b-5">Samsung Note 4</h5>
											</td>
											<td>$500.00</td>
											<td>13/02/2013</td>
										</tr>
									</tbody>
								</table>
                                <!-- end table -->
							</div>
                            <!-- end scrollbar -->
                        </div>
                        <!-- end col-4 -->
                        <!-- begin col-4 -->
                        <div class="col-md-4">
                            <h4 class="title">Task <small>24 pending</small></h4>
                            <!-- begin scrollbar -->
                            <div data-scrollbar="true" data-height="280px" class="bg-silver">
                                <!-- begin todolist -->
                                <ul class="todolist">
                                    <li class="active">
                                        <a href="javascript:;" class="todolist-container active" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="todolist-container" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Duis a ullamcorper massa.</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="todolist-container" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Phasellus bibendum, odio nec vestibulum ullamcorper.</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="todolist-container" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Duis pharetra mi sit amet dictum congue.</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="todolist-container" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Duis pharetra mi sit amet dictum congue.</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="todolist-container" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Phasellus bibendum, odio nec vestibulum ullamcorper.</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="todolist-container active" data-click="todolist">
                                            <div class="todolist-input"><i class="fa fa-square-o"></i></div>
                                            <div class="todolist-title">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</div>
                                        </a>
                                    </li>
                                </ul>
                                <!-- end todolist -->
                            </div>
                            <!-- end scrollbar -->
                        </div>
                        <!-- end col-4 -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end profile-section -->
            </div>
			<!-- end profile-container -->
		</div>
		<!-- end #content -->
		
        <!-- begin theme-panel -->
        <div class="theme-panel">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5 class="m-t-0">Color Theme</h5>
                <ul class="theme-list clearfix">
                    <li class="active"><a href="javascript:;" class="bg-green" data-theme="default" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-red" data-theme="red" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-blue" data-theme="blue" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Blue">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-black" data-theme="black" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Black">&nbsp;</a></li>
                </ul>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Header Styling</div>
                    <div class="col-md-7">
                        <select name="header-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">inverse</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label">Header</div>
                    <div class="col-md-7">
                        <select name="header-fixed" class="form-control input-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Sidebar Styling</div>
                    <div class="col-md-7">
                        <select name="sidebar-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">grid</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label">Sidebar</div>
                    <div class="col-md-7">
                        <select name="sidebar-fixed" class="form-control input-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Sidebar Gradient</div>
                    <div class="col-md-7">
                        <select name="content-gradient" class="form-control input-sm">
                            <option value="1">disabled</option>
                            <option value="2">enabled</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Content Styling</div>
                    <div class="col-md-7">
                        <select name="content-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">black</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-inverse btn-block btn-sm" data-click="reset-local-storage"><i class="fa fa-refresh m-r-3"></i> Reset Local Storage</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
@endsection

@push('styles')
@endpush

@push('plugin-scripts')
@endpush

@push('custom-scripts')
@endpush

@push('on-ready-scripts')
@endpush