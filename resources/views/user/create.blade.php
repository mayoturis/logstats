@extends('empty')

@section('title', 'Login')

@section('head')
	{!! Html::style('public/libraries/bootstrap/bootstrap.min.css') !!}
	{!! Html::style('public/css/main.css') !!}
@endsection

@section('content')
	<div class="container">
		<form class="login-form" method="POST" action="{{ route('user.store') }}">
			@include('partials.form_errors', ['messageBag' => 'register'])
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input class="form-control" type="text" name="name" placeholder="Username" value="{{ old('name') }}">
			<input class="form-control" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
			<input class="form-control" type="password" name="password" placeholder="Password">
			<input class="form-control" type="password" name="password_confirmation" placeholder="Repeat assword">
			<input class="form-control" type="submit" value="Register">
		</form>
	</div>
@endsection