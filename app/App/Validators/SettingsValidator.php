<?php  namespace Logstats\App\Validators; 

class SettingsValidator extends AbstractValidator {
	public function isValidSettings($data) {
		return $this->isValid($data, [
			'timezone' => 'required|timezone',
		]);
	}
}