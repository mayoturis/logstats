@extends('adminlte')

@section('title', 'Segmentation')
@section('content-header', 'Segmentation')

@section('content')
	<div class="segmentation" data-property-names-url="{{ route('ajax-property-names') }}">
		<form id="query-form">
			<input type="hidden" id="project-id" name="project-id" value="{{ $project->getId() }}">
			<input type="hidden" id="project-token" name="project-token" value="{{ $project->getToken() }}">
			<input type="hidden" name="query-url" value="{{ route('query') }}">
			<div class="event-choose">
				<div class="input-group level">
					<span class="input-group-addon" id="basic-addon1">Level</span>
					<select class="form-control" name="level">
						<option value="">ALL</option>
						<option value="emergency">emergency</option>
						<option value="alert">alert</option>
						<option value="critical">critical</option>
						<option value="error">error</option>
						<option value="warning">warning</option>
						<option value="notice">notice</option>
						<option value="info" selected>info</option>
						<option value="debug">debug</option>
					</select>
				</div>

				<div class="input-group">
					<span class="input-group-addon">Event</span>
					<select data-url="{{ route('ajax-messages') }}" class="form-control" name="event" id="event">
					</select>
				</div>
			</div>

			<div class="upper-bar">
				<div class="input-group group-by">
					<span class="input-group-addon">Group by</span>
					<select class="form-control property-options" name="groupBy">
					</select>
				</div>

				<div class="input-group aggregation">
					<span class="input-group-addon">Analysis type</span>
					<select class="form-control" name="aggregate">
						<option value="count">Count</option>
						<option value="sum">Sum</option>
						<option value="avg">Average</option>
						<option value="min">Min</option>
						<option value="max">Max</option>
					</select>
				</div>

				<div class="input-group target-property">
					<span class="input-group-addon">Target property</span>
					<select class="form-control property-options" disabled name="targetProperty">
					</select>
				</div>

				<div class="input-group interval">
					<span class="input-group-addon">Interval</span>
					<select class="form-control" name="interval">
						<option>None</option>
						<option value="minutely">Minutely</option>
						<option value="hourly">Hourly</option>
						<option value="daily">Daily</option>
						<option value="monthly">Monthly</option>
						<option value="yearly">Yearly</option>
					</select>
				</div>
				@include('partials.daterange')

				<button type="button" class="maincolor-box btn add-filter">
					<i class="fa fa-plus"></i> Filters
				</button>
				<button class="maincolor-box btn" id="run-query">
					<i class="fa fa-search"></i> Find
				</button>

			</div>
			<div class="down-control">
				<div class="filters">

				</div>

				<div class="maincolor-box btn" id="add-filter-row">
					<i class="fa fa-plus"></i>
				</div>

				<div id="example-filter-row">
					<select class="form-control property-name property-options">
					</select>
					<select class="form-control comparison">
						<option value="equal" class="string number boolean">Equal to</option>
						<option value="not_equal" class="string number">Not equal to</option>
						<option value="greater" class="string number">Greater than</option>
						<option value="less" class="string number">Less than</option>
						<option value="greater_or_equal" class="string number">Greater than or equal to</option>
						<option value="less_or_equal" class="string number">Less than or equal to</option>
						<option value="contains" class="string">Contains</option>
						<option value="not_contains" class="string">Does not contains</option>
					</select>
					<input class="form-control value" type="text" placeholder="Value...">
					<i class="fa fa-times-circle remove-filter-row"></i>
				</div>
			</div>
		</form>
		<div class="graph">
			<div class="graph-area">
			</div>
			<div class="graph-tooltip"></div>
			<div class="graph-checkboxes"></div>
		</div>
		<div id="export-image" class="btn maincolor-box">
			Export as image
		</div>
	</div>
	<div class="loader" id="loader"></div>
@endsection