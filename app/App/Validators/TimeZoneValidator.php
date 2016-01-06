<?php  namespace Logstats\App\Validators;

class TimeZoneValidator extends AbstractValidator {
	private $rules = [
		"timezone" => "required|timezone"
	];

	public function isValidTimezone(array $input) {
		return $this->isValid($input, $this->rules);
	}
}