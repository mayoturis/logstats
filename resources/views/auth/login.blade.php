@extends('empty')

@section('title', 'Login')

@section('head')
	{!! Html::style('public/libraries/bootstrap/bootstrap.min.css') !!}
	{!! Html::style('public/css/main.css') !!}
@endsection

@section('content')
	<div class="container">
		<form class="login-form" method="POST" action="{{ URL::to('auth/login') }}">
			@include('partials.form_errors', ['messageBag' => 'login'])
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input class="form-control" type="text" id="name" name="name" placeholder="Username">
			<input class="form-control" type="password" id="password" name="password" placeholder="Password">
			<input type="checkbox" id="remember_me" name="remember_me"> <label for="remember_me">Remember me</label>
			<input class="form-control" type="submit" value="Login">
			<a href="{{ route('register') }}">(Register)</a>
		</form>
	</div>
@endsection