@extends('installation.base')

@section('title', 'Congratulations')

@section('content')
	<h1>Congratulations!</h1>
	<p>
		Logstats was successfully installed
	</p>

	<a class="step next" href="{{ route('home') }}">
		Home page >>
	</a>
@endsection