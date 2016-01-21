@extends('empty')

@section('content')
	Error occured while accessing database. Check .env file in route of this project whether database
	access data are correct. <br><br>
	Full error message: {!! $message !!}

	<br><br>
	<a href="{{ route('home') }}">Try to continue</a>
@endsection

