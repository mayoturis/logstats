@extends('adminlte')

@section('title', 'Unknown error')

@section('content')
	<div class="big-error-message">
		Unknown error occured. You can continue on <a href="{{ route('home') }}">home page</a>
	</div>
@endsection