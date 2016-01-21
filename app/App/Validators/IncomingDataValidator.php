<?php  namespace Logstats\App\Validators;

class IncomingDataValidator extends AbstractValidator {

	private $rootRules = [
		'project' => 'required|string',
		'messages' => 'required'
	];

	private $messageRules = [
		'level' => 'required|in:emergency,alert,critical,error,warning,notice,info,debug',
		'message' => 'required|string',
		'time' => 'required|integer'
	];


	public function isValidRoot($data) {
		return $this->isValid($data, $this->rootRules) && is_array(json_decode($data['messages']));
	}

	public function isValidRecord($data) {
		return $this->isValid($data, $this->messageRules);
	}
}