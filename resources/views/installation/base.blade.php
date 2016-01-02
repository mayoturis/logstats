<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	{!! Html::script('public/libraries/jquery/jquery.min.js') !!}
	{!! Html::style('public/libraries/bootstrap/bootstrap.min.css') !!}
	{!! Html::script('public/libraries/bootstrap/bootstrap.min.js') !!}
	{!! Html::script('public/libraries/select2/dist/js/select2.min.js') !!}
	{!! Html::style('public/libraries/select2/dist/css/select2.css') !!}
	{!! Html::style('public/css/main.css') !!}
	{!! Html::style('public/css/installation.css') !!}
	{!! Html::script('public/js/installation.js') !!}
	<title>Installation - @yield('title')</title>
</head>
<body class="installation">
	<div class="container">
		<div class="row">
			<div class="col-xs-3" style="height: 100px;">
				Logstats logo
			</div>
			<div class="col-xs-9">
				# Some title
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<nav>
					@inject('installationSteps', '\Logstats\Services\Installation\StepCollection')
					<ol>
						@foreach($installationSteps->getSteps() as $key => $step)
							@if(!isset($step['notShow']))
								<li class="{{ set_active('installation/1/'.$key) ? set_active('installation/1/'.$key) : set_active('installation/2/'.$key) }}">{{ $step['menu'] }}</li>
							@endif
						@endforeach
					</ol>
				</nav>
			</div>
			<div class="col-xs-9">
				@yield('content')
			</div>
		</div>
	</div>
</body>
</html>
