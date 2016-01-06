<?php  namespace Logstats\App\Validators\Rules;

class InstallationRules {

	/**
	 * @param $attribute
	 * @param $value File name
	 * @param $parameters
	 * @param $validator
	 * @return bool
	 */
	public function fileCanBeCreated($attribute, $value, $parameters, $validator) {
		return true;
	}
}