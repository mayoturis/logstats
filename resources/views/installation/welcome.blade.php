@extends('installation.base')

@section('title', 'Welcome')

@section('content')
	<h1>Welcome!</h1>
	<p>
		This is an installation guide. After these 6 steps your software will be ready.
		It shouldn't take more than 5 minutes.
	</p>
	@inject('installationSteps', '\Logstats\Services\Installation\StepCollection')
	<a class="step next" href="{!! URL::route('installation', ['step' => $installationSteps->nextKeyForShort('welcome')]) !!}">
		Next step >>
	</a>
@endsection