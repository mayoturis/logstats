@extends('adminlte')

@section('title', 'Projects')

@section('content-header', 'Projects')

@section('content')
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
			</div>
			<div class="box-body">
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