
@if($errors->$messageBag->count() > 0)
<div class="bg-danger errors message-div">
	<ul class="list-unstyled">
		@foreach($errors->$messageBag->all() as $message)
			<li>{{ $message }}</li>
		@endforeach
	</ul>
</div>
@endif