<?php  namespace Logstats\Services\Validators; 

class UserValidator extends AbstractValidator {
	private $create = [
		"name" => "required|min:3|unique:users,name|max:255",
		"password" => "required|min:5|confirmed|max:60",
		"email" => "email|unique:users,email|max:255"
	];

	public function isValidForCreate($input) {
		return $this->isValid($input, $this->create);
	}
}