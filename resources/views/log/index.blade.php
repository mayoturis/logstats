@extends('adminlte')

@section('title', 'Log')

@section('content-header', 'Log')

@section('content')
	<div class="log">
		<form method="get" id="get-records" action="{{ route('ajax-get-records') }}">
			<input type="hidden" name="project-id" value="{{ $project->getId() }}">
			<div class="upper-control">
				<input class="form-control" name="message-search" placeholder="Message contains...">
				@include('partials.daterange')
				<div class="maincolor-box btn add-filter">
					<i class="fa fa-plus"></i> Filters
				</div>
				<button class="maincolor-box btn">
					<i class="fa fa-search"></i> Find
				</button>
				<button class="maincolor-box btn" id="export-csv" data-export-csv-url="{{ route('export-csv') }}">
					<i class="fa fa-file-code-o"></i> Export in CSV
				</button>
			</div>
			<div class="down-control">
				<div>
					<select class="form-control" name="level">
						<option value="">All levels</option>
						@include('partials.level_options')
					</select>
				</div>
				<div class="filters">

				</div>
				<div class="maincolor-box btn" id="add-filter-row">
					<i class="fa fa-plus"></i>
				</div>

				<div id="example-filter-row">
					<input class="form-control property-name" type="text" placeholder="Property name...">
					<select class="form-control property-type">
						<option value="string">String</option>
						<option value="number">Number</option>
						<option value="boolean">Boolean</option>
					</select>
					<select class="form-control comparison">
						<option value="equal" class="string number boolean">Equal to</option>
						<option value="not-equal" class="string number">Not equal to</option>
						<option value="greater" class="string number">Greater than</option>
						<option value="less" class="string number">Less than</option>
						<option value="greater-equal" class="string number">Greater than or equal to</option>
						<option value="less-equal" class="string number">Less than or equal to</option>
						<option value="contains" class="string">Contains</option>
						<option value="not-contains" class="string">Does not contains</option>
					</select>
					<input class="form-control value" type="text" placeholder="Value...">
					<i class="fa fa-times-circle remove-filter-row"></i>
				</div>
			</div>
			<input type="hidden" name="page" value="1">
			<input type="hidden" name="page-count" value="100">
		</form>
		<div class="log-graph">
			<div class="log-graph-area"></div>
			<div class="graph-tooltip"></div>
		</div>
		<div class="log-records">
		</div>
		<div class="page-numbers">
			<nav>
				<ul class="pagination">
				</ul>
			</nav>
		</div>
	</div>
	<div class="loader" id="loader"></div>
@endsection