@extends('adminlte')

@section('title', 'Settings')

@section('content-header', 'Settings')

@section('content')
	@include('partials.form_errors', ['messageBag' => 'settings'])
	@include('partials.flash_div_message')
	<form method="POST" action="{{ route('settings-store') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		Application timezone (all users):
		<select name="timezone">
			@include('partials.timezone_options')
		</select>
		<input type="submit" value="save" class="btn maincolor-box">

	</form>

	<script>
		$(document).ready(function() {
			$("select[name='timezone']").select2();
			$("select[name='timezone']").val('{{ $timezone }}').trigger("change");
		})
	</script>
@endsection