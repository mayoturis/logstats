<?php  namespace Logstats\App\Validators; 

use Logstats\Domain\Record\Level;

class AlertingValidator extends AbstractValidator {



	public function isValidLevelEmailAlerting($data) {
		$rules = [
			'email' => 'required|email',
			'level' => 'required|in:' . join(',', Level::getAll()),
			'project_id' => 'required'
		];

		return $this->isValid($data, $rules);
	}
}