<?php  namespace Logstats\App\Validators;

class ProjectValidator extends AbstractValidator{
	private $createRules = [
		"name" => "required|string|unique:projects,name"
	];

	public function isValidForCreate($input) {
		return $this->isValid($input, $this->createRules);
	}
}