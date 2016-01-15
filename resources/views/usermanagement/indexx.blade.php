@extends('adminlte')

@section('title', 'User management')
@section('content-header', 'User management')

@section('content')
	<div class="userManagement">
		@include('partials.form_errors', ['messageBag' => 'userManagement'])
		@include('partials.flash_div_message')
		<ul class="nav nav-tabs">
			<li role="presentation" class="active" data-id="all"><a href="#">All projects</a></li>
			@foreach($projectProjectRoleListDTOs as $projectProjectRoleListDTO)
				<li role="presentation" data-id="{{ $projectProjectRoleListDTO->getProject()->getId() }}"><a href="#">{{ $projectProjectRoleListDTO->getProject()->getName() }}</a></li>
			@endforeach
		</ul>
		<div class="tab-div" id="all" style="display:block">
			<h4>All projects</h4>
			<form action="{{ route('user-management-all') }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<table class="user-management-table">
					<tr>
						<th>Username</th>
						<th>None</th>
						<th>Visitor</th>
						<th>Admin</th>
						<th></th>
					</tr>
					@foreach($users as $oneUser)
						<tr>
							<td>{{ $oneUser->getName() }}</td>
							<td><input type="radio" name="users[{{ $oneUser->getId() }}][role]" value="" {{ $oneUser->isGeneralVisitor() ? "" : "checked" }}></td>
							<td><input type="radio" name="users[{{ $oneUser->getId() }}][role]" value="visitor" {{ $oneUser->isGeneralVisitor() ? "checked" : "" }}></td>
							<td><input type="radio" name="users[{{ $oneUser->getId() }}][role]" value="admin" {{ $oneUser->isGeneralAdmin() ? "checked" : "" }}></td>
							<td>
								@if($oneUser->getId() != $user->getId())
									<form class="no-style-form" action="{{ route('user.destroy', ['id' => $oneUser->getId()]) }}" method="post">
										{{ csrf_field() }}
										{{ method_field('delete') }}
										<a href="javascript:void(0)" class="submitable-link">Delete user</a>
									</form>
								@endif
							</td>
						</tr>
					@endforeach
				</table>
				<input class="btn maincolor-box" type="submit" value="Save">
			</form>
		</div>

		@foreach($projectProjectRoleListDTOs as $projectProjectRoleListDTO)
			<div class="tab-div" id="{{ $projectProjectRoleListDTO->getProject()->getId() }}">
				<h4>{{ $projectProjectRoleListDTO->getProject()->getName() }}</h4>
				<form action="{{ route('user-management-project') }}" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="project-id" value="{{ $projectProjectRoleListDTO->getProject()->getId() }}">
					<table class="user-management-table">
						<tr>
							<th>Username</th>
							<th>None</th>
							<th>Visitor</th>
							<th>Admin</th>
						</tr>
						@foreach($users as $oneUser)
							<tr>
								<td>{{ $oneUser->getName() }}</td>
								<td><input type="radio" name="users[{{ $oneUser->getId() }}][role]" value="" {{ $projectProjectRoleListDTO->getProjectRoleList()->isVisitor($oneUser) ? "" : "checked" }}></td>
								<td><input type="radio" name="users[{{ $oneUser->getId() }}][role]" value="visitor" {{ $projectProjectRoleListDTO->getProjectRoleList()->isVisitor($oneUser) ? "checked" : "" }}></td>
								<td><input type="radio" name="users[{{ $oneUser->getId() }}][role]" value="admin" {{ $projectProjectRoleListDTO->getProjectRoleList()->isAdmin($oneUser) ? "checked" : "" }}></td>
							</tr>
						@endforeach
					</table>
					<input class="btn maincolor-box" type="submit" value="Save">
				</form>
			</div>
		@endforeach
	</div>
@endsection