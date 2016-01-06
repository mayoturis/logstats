@extends('installation.base')

@section('title', 'Creating the tables')

@section('content')


	@if (!empty($errorMessage))
		<div class="bg-danger message-div">
			Error occured while creating tables. It is possible that configuration
			data provided in previous step are incorrect. <br>
			Error message: <br>
			{!! $errorMessage !!}
		</div>

		<a href="{{ URL::route('installation', ['step' => $installationSteps->getKeyByShort('databaseSetup')]) }}" class="step previous">
			<< Database setup
		</a>
	@else
		<div class="bg-success message-div">
			Tables were successfully created
		</div>

		<a href="{{ URL::route('installation', ['step' => $installationSteps->nextKeyForShort('createTables')]) }}" class="step next">
			Next step >>
		</a>
	@endif

@endsection
