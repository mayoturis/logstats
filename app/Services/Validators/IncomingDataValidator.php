<?php  namespace Logstats\Services\Validators;

class IncomingDataValidator extends AbstractValidator {

	private $rootRules = [
		'project' => 'required|string|exists:projects,token',
		'messages' => 'required|array'
	];

	private $messageRules = [
		'level' => 'required|in:emergency,alert,critical,error,warning,notice,info,debug',
		'message' => 'required|string',
		'time' => 'required|integer'
	];


	public function isValidRoot($data) {
		return $this->isValid($data, $this->rootRules);
	}

	public function isValidRecord($data) {
		return $this->isValid($data, $this->messageRules);
	}
}