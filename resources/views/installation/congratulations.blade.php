@extends('installation.base')

@section('title', 'Congratulations')

@section('content')
	<h1>Congratulations!</h1>
	<p>
		Logstats was successfully installed
	</p>

	<a class="step next" href="{{ route('how-to-send-logs') }}">
		How to send logs >>
	</a>
@endsection