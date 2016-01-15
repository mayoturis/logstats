@extends('adminlte')

@section('title', 'Project management')
@section('content-header', 'Project management')

@section('content')
	@include('partials.flash_div_message')
	@can('deleteRecords', [$currentProject])
	<h4>Record deleting</h4>
	<div class="grey-info">(All records from project '{{ $currentProject->getName() }}' will be deleted forever)</div>
	<form id="delete-logs" method="post" action="{{ route('project-management.deleteRecords') }}">
		<input type="hidden" name="project-id" value="{{ $currentProject->getId() }}">
		{{ csrf_field() }}
		{{ method_field('delete') }}
		<input type="submit" class="btn maincolor-box" value="Delete all records">
	</form>
	@endcan
	<script>
		$(document).ready(function() {
			$("#delete-logs").submit(function(e) {
				if (!confirm("Are you sure you want to delete all records forever?")) {
					e.preventDefault();
					return false;
				}
			});
		});
	</script>
@endsection