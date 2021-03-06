
@foreach($records as $record)
	<div class="box box-default collapsed-box">
		<div class="box-header with-border">
			<div class="text" >
				[{{ $record->getDate() }}] - {{ $record->getLevel() }} - {{ $record->getMessage() }} - {{ json_encode($record->getContext(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}
			</div>
			<div class="more box-tools pull-right">
				<a class="btn btn-box-tool" data-widget="collapse">Show more</a>
			</div>
		</div>
		<div class="box-body">
			<b>Date: </b>{{ $record->getDate()}} <br>
			<b>Level: </b>{{ $record->getLevel() }}<br>
			<b>Message: </b>{!! nl2br(e($record->getMessage())) !!}<br>
			<b>Context: </b><br>
			<pre>{{ json_encode($record->getContext(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
		</div>

	</div>
@endforeach