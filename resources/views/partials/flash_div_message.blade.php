@if(session()->has('flash_message'))
	<div class="bg-{{ session()->has('flash_type') ? session('flash_type') : 'info' }} message-div">
		{{ session('flash_message') }}
	</div>
@endif