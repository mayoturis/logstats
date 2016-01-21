@extends('adminlte')

@section('title', '404 - page not found');

@section('content')
	<div class="big-error-message">
		404 - page was not found. You can continue on <a href="{{ route('home') }}">home page</a>
	</div>
@endsection