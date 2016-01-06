@extends('installation.base')

@section('title', 'General setup')

@section('content')

	<form method="POST" action="{{ URL::route('installation', ['step' =>  $installationSteps->getKeyByShort('generalSetup')])}}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		@include('partials.form_errors', ['messageBag' => 'generalSetupUser'])
		<table class="table">
			<tr>
				<td colspan="2"><strong>Super user</strong></td>
			</tr>
			<tr>
				<td><label for="name">Username:</label></td>
				<td><input type="text" id="name" name="name" value="{{ old('name') }}"></td>
			</tr>
			<tr>
				<td><label for="email">Email: </label><br><span class="grey-info">(Optional)</span></td>
				<td><input type="email" id="email" name="email" value="{{ old('email') }}"></td>
			</tr>
			<tr>
				<td><label for="password">Password: </label></td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr>
				<td><label for="password_confirmation">Password (repeat):</label></td>
				<td><input type="password" name="password_confirmation" id="password_confirmation"></td>
			</tr>
		</table>
		@include('partials.form_errors', ['messageBag' => 'generalSetupTimezone'])
		<table class="table">
			<tr>
				<td colspan="2"><strong>Timezone</strong></td>
			</tr>
			<tr>
				<td><label for="timezone">Timezone: </label></td>
				<td>
					<select name="timezone" id="timezone">
						@include('partials.timezone_options')
					</select>
				</td>
			</tr>
		</table>

		@include('partials.form_errors', ['messageBag' => 'generalSetupProject'])
		<table class="table">
			<tr>
				<td colspan="2"><strong>First project</strong><br><span class="grey-info">Can be created later</span></td>
			</tr>
			<tr>
				<td><label for="project_name">Project name</label></td>
				<td><input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}"></td>
			</tr>
		</table>
		<input type="submit" value="Save">
	</form>
@endsection