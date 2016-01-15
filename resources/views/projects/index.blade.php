@extends('adminlte')

@section('title', 'Projects')

@section('content-header', 'Projects')

@section('content')
	@include('partials.flash_div_message')
	@can('create.project')
		<a class="btn maincolor-box" href="{{ route('projects.create') }}">
			<i class="fa fa-plus"></i> New project
		</a><br><br>
	@endcan
	@foreach($projectdtos as $projectdto)
		<div class="box">
			<div class="box-header">
				<div class="box-title">
					<a href="{{ route('projects.show', ['id' => $projectdto->getProject()->getId()]) }}">{{ $projectdto->getProject()->getName() }}</a>
				</div>
				@can('delete', $projectdto->getProject())
				<div class="pull-right">
					<form action="{{ route('projects.destroy', ['id' => $projectdto->getProject()->getId()]) }}" method="POST">
						{{ method_field('delete') }}
						{{ csrf_field() }}
						<a class="delete-project" data-project-name="{{ $projectdto->getProject()->getName() }}" href="javascript:void(0)">Delete project</a>
					</form>
				</div>
				@endcan
			</div>
			<div class="box-body">
				Token: {{ $projectdto->getProject()->getToken() }}<br>
			    Created at: {{ $projectdto->getProject()->getCreatedAt() }}
				<div class="pull-right">
					@if($projectdto->getLastRecordDate() === null)
						No records
					@else
						Last record: {{ $projectdto->getLastRecordDate() }}
					@endif
				</div>
			</div>
		</div>
	@endforeach
@endsection