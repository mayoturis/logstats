<?php  namespace Logstats\Services\Validators; 

class ProjectValidator extends AbstractValidator{
	private $createRules = [
		"name" => "required|unique:projects,name"
	];

	public function isValidForCreate($input) {
		return $this->isValid($input, $this->createRules);
	}
}