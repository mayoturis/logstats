<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('title')</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	{!! Html::style('public/libraries/bootstrap/bootstrap.min.css') !!}
	{!! Html::style('public/libraries/font-awesome/css/font-awesome.min.css') !!}
	{!! Html::style('public/libraries/adminlte/dist/css/AdminLTE.min.css') !!}
	{!! Html::style('public/libraries/adminlte/dist/css/skins/skin-green.min.css') !!}
	{!! Html::style('public/libraries/daterangepicker/daterangepicker.css') !!}
	{!! Html::style('public/libraries/select2/dist/css/select2.css') !!}
	{!! Html::style('public/libraries/select2/dist/css/bootstrap-theme.css') !!}

	{!! Html::script('public/libraries/jquery/jquery.min.js') !!}
	{!! Html::script('public/libraries/bootstrap/bootstrap.min.js') !!}
	{!! Html::script('public/libraries/adminlte/dist/js/app.min.js') !!}
	{!! Html::script('public/libraries/moment/moment.min.js') !!}
	{!! Html::script('public/libraries/moment/moment-timezone-with-data.min.js') !!}
	{!! Html::script('public/libraries/chained/jquery.chained.min.js') !!}
	{!! Html::script('public/libraries/daterangepicker/daterangepicker.js') !!}
	{!! Html::script('public/libraries/select2/dist/js/select2.min.js') !!}
	{!! Html::script('public/libraries/flot/timezone.js') !!}
	{!! Html::script('public/libraries/flot/jquery.flot.min.js') !!}
	{!! Html::script('public/libraries/flot/jquery.flot.time.min.js') !!}
	{!! Html::script('public/libraries/flot/jquery.flot.selection.min.js') !!}
	{!! Html::script('public/libraries/serializeobject/jquery.serialize-object.min.js') !!}
	{!! Html::script('public/libraries/html2canvas/html2canvas.min.js') !!}

	{!! Html::script('public/js/main.js') !!}
	{!! Html::style('public/css/main.css') !!}
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
	<header class="main-header">
		<a href="{{ route('home') }}" class="logo">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><b>L</b></span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><b>Logstats</b></span>
		</a>

		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<!-- User Account Menu -->
					<li class="user user-menu">
						<!-- Menu Toggle Button -->
						<a href="#">
							<span class="hidden-xs">{{ $user->getName() }}</span>
						</a>
					</li>
					<li>
						<a href="{{ route('logout') }}" style="padding-top: 18px;"><i class="fa fa-power-off"></i></a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">

		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar Menu -->
			<ul class="sidebar-menu">
				@if(!empty($currentProject))
					<li class="header project-name">{{ $currentProject->getName() }}</li>
					<li class="{{ set_active('log') }}"><a href="{{ route('log') }}"><i class="fa fa-list-ul"></i><span>Log records</span></a></li>
					<li class="{{ set_active('segmentation') }}"><a href="{{ route('segmentation') }}"><i class="fa fa-bar-chart"></i><span>Segmentation</span></a></li>
					@can('manageAlerting', [$currentProject])
						<li class="{{ set_active('alerting') }}"><a href="{{ route('alerting.index') }}"><i class="fa fa-envelope"></i><span>Email notifications</span></a>
					@endcan
					@can('deleteRecords', [$currentProject])
						<li class="{{ set_active('project-management') }}"><a href="{{ route('project-management') }}"><i class="fa fa-pencil-square-o"></i><span>Project management</span></a></li>
					@endcan
				@endif
				<li class="header">General</li>
				<li class="{{ set_active('projects') }} {{ set_active('/') }}"><a href="{{ route('projects.index') }}"><i class="fa fa-exchange"></i><span>Projects</span></a></li>
				@if($user->isGeneralAdmin())
					<li class="{{ set_active('user-management') }}"><a href="{{ route('user-management') }}"><i class="fa fa-user"></i><span>Users</span></a></li>
					<li class="{{ set_active('settings') }}"><a href="{{ route('settings') }}"><i class="fa fa-cog"></i><span>Settings</span></a></li>
				@endif
				<li class="{{ set_active('how-to-send-logs') }}"><a href="http://logstats.org/introduction" target="_blank"><i class="fa fa-question-circle"></i><span>Help</span></a></li>
			</ul>
			<!-- /.sidebar-menu -->
		</section>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				@yield('content-header')
			</h1>
		</section>

		<!-- Main content -->
		<section class="content">
			@yield('content')
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<!-- Main Footer -->
	<footer class="main-footer">
		<!-- To the right -->
		<div class="pull-right hidden-xs">
			marek.turis@gmail.com
		</div>
		<!-- Default to the left -->
		<strong>Copyright &copy; 2016 Marek Turis</strong>
	</footer>
</div>
<div id="data-holder" data-timezone="{{ $timezone }}"></div>
</body>
</html>

