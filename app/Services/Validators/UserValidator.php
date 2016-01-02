<?php  namespace Logstats\Services\Validators; 

class UserValidator extends AbstractValidator {
	private $create = [
		"name" => "required|string|min:3|unique:users,name|max:255",
		"password" => "required|string|min:5|confirmed|max:60",
		"email" => "email|string|unique:users,email|max:255"
	];

	public function isValidForCreate($input) {
		return $this->isValid($input, $this->create);
	}
}