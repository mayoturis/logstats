@extends('adminlte')

@section('title', 'New project')

@section('content-header', 'New project')

@section('content')
	<div class="row">
		<div class="col-xs-4">
			@include('partials.form_errors', ['messageBag' => 'createProject'])
			<form method="post" action="{{ route('projects.store') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group">
					<label for="name">Project name:</label>
					<input class="form-control" type="text" placeholder="Project name" id="name" name="name">
				</div>
				<input class="btn maincolor-box" type="submit" value="Create">
			</form>
		</div>
		<div class="col-xs-4"></div>
		<div class="col-xs-4"></div>
	</div>

@endsection