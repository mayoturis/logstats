@extends('adminlte')

@section('title', 'Access denied');

@section('content')
	<div class="big-error-message">
		Access denied. You don't have access to this page. You can ask admin for access rights.
		Continue on <a href="{{ route('home') }}">home page</a>
	</div>
@endsection