@extends('adminlte')

@section('title', 'Email alerting')

@section('content-header')
	Email alerting in project {{ $currentProject->getName() }}
@endsection

@section('content')
	<div class="alerting">
		@include('partials.flash_div_message')
		<h4>Current email addresses</h4>
		<table class="current-alerting">
			<tr>
				<th>Level</th>
				<th>Email</th>
				<th></th>
			</tr>
			@foreach($alertings as $alerting)
				<tr>
					<td>{{ $alerting->getLevel() }}</td>
					<td>{{ $alerting->getEmail() }}</td>
					<td>
						<form class="no-style-form" action="{{ route('alerting.destroy', ['id' => $alerting->getId()]) }}" method="post">
							{{ csrf_field() }}
							{{ method_field('delete') }}
							<a href="javascript:void(0)" class="submitable-link">Delete</a>
						</form>
					</td>
				</tr>
			@endforeach
		</table>

		<h4>Add new</h4>
		@include('partials.form_errors', ['messageBag' => 'alerting'])
		<form method="post" action="{{ route('alerting.store') }}">
			{{ csrf_field() }}
			<input type="hidden" name="project_id" value="{{ $currentProject->getId() }}">
			<input type="email" name="email" placeholder="Email...">
			for level
			<select name="level">
				@include('partials.level_options')
			</select>
			<input class="btn maincolor-box" type="submit" value="Save">
		</form>
	</div>
@endsection
