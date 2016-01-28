@extends('installation.base')

@section('title', 'Database setup')

@section('content')
	@include('partials.form_errors', ['messageBag' => 'databaseSetup'])

	<form method="POST" action="{{ URL::route('installation', ['step' =>  $installationSteps->getKeyByShort('databaseSetup')])}}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<table class="table database-setup">
			<tr>
				<td><label for="database_type">Database type: </label></td>
				<td>
					<select name="database_type" id="database_type">
						<option value="mysql" {{ old_select('database_type', 'mysql', 'selected') }}>MySQL</option>
						<option value="sqlite" {{ old_select('database_type', 'sqlite') }}>SQLite</option>
						<option value="pgsql" {{ old_select('database_type', 'pgsql') }}>PostgreSQL</option>
						<option value="mssql" {{ old_select('database_type', 'mssql') }}>Microsoft SQL Server (not tested)</option>
					</select>
				</td>
			</tr>
			<tr data-databases="mysql pgsql mssql">
				<td><label for="host">Database server: </label></td>
				<td><input type="text" name="host" id="host" value="{{ old('host') }}"></td>
			</tr>
			<tr data-databases="mysql pgsql mssql">
				<td><label for="database">Database name: </label><br>
					<span class="grey-info">(Database has to already exist)</span></td>
				<td><input type="text" name="database" id="database" value="{{ old('database') }}"></td>
			</tr>
			<tr data-databases="mysql pgsql mssql">
				<td><label for="username">Username</label></td>
				<td><input type="text" name="username" id="username" value="{{ old('username') }}"></td>
			</tr>
			<tr data-databases="mysql pgsql mssql">
				<td><label for="password">Password: </label></td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr data-databases="mysql pgsql mssql sqlite">
				<td>
					<label for="prefix">Table prefix: </label><br>
					<span class="grey-info">(to avoid collisions)</span>
				</td>
				<td><input type="text" name="prefix" id="prefix" value="{{ old('prefix', 'logstats_') }}"></td>
			</tr>
			<tr data-databases="mysql pgsql mssql">
				<td><label for="charset">Charset: </label></td>
				<td><input type="text" name="charset" id="charset" value="{{ old('charset', 'utf8') }}"></td>
			</tr>
			<tr data-databases="mysql">
				<td><label for="collation">Collaction</label></td>
				<td><input type="text" name="collation" id="collation" value="{{ old('collation', 'utf8_unicode_ci') }}"></td>
			</tr>
			<tr data-databases="sqlite">
				<td><label for="database_location">Database location: </label><br>
					<span class="grey-info">(File has to already exist)</span></td>
				<td><input type="text" name="database_location" id="database_location" value="{{ old('database_location',storage_path('database.sqlite')) }}"></td>
			</tr>
			<tr data-databases="pgsql">
				<td><label for="schema">Schema</label></td>
				<td><input type="text" name="schema" id="schema" value="{{ old('schema', 'public') }}"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Save"></td>
			</tr>
		</table>
	</form>

@endsection