@extends('adminlte')

@section('title', 'How to send logs')

@section('content-header', 'How to send logs')

@section('content')
	<select class="form-control">
		<option>sf</option>
	</select>
	<div class="form-control daterange" id="daterange">
		<i class="fa fa-calendar"></i><label></label>
	</div>
	<input type="hidden" name="from">
	<input type="hidden" name="to">
@endsection