<?php  namespace Logstats\App\Validators;

class RecordValidator extends AbstractValidator {

	private $filterRules = [
		'property-name' => 'required',
		'property-type' => 'required|in:string,number,boolean',
		'property-value' => 'required',
		'comparison-type' => 'required|in:equal,not-equal,greater,less,greater-equal,less-equal,contains,not-contains',
	];

	public function isValidFilter($data) {
		return $this->isValid($data, $this->filterRules);
	}
}