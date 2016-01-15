<div>
	<h3>New record arrived</h3>
	<div>
		<b>Date: </b>{{ $record->getDate()}} <br>
		<b>Level: </b>{{ $record->getLevel() }}<br>
		<b>Message: </b>{!! nl2br(e($record->getMessage())) !!}<br>
		<b>Properties: </b><br>
		<pre>{{ json_encode($record->getContext(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
	</div>
</div>