<?php  namespace Logstats\App\Validators;


use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Factory;

abstract class AbstractValidator {

	/**
	 *	@var \Illuminate\Contracts\Validation\Factory
	 */
	private $validator;
	/**
	 * @var MessageBag
	 */
	private $errors;

	/**
	 * @param Factory $validator
	 */
	public function __construct(Factory $validator) {
		$this->validator = $validator;
	}

	/**
	 * @param array $input
	 * @param array $rules
	 * @param array $messages
	 * @param array $customAttributes
	 * @return bool
	 */
	public function isValid(array $input, array $rules, $messages = [], $customAttributes = []) {
		$v = $this->validator->make($input, $rules, $messages, $customAttributes);

		if ($v->fails()) {
			$this->addErrors($v->messages());
			return false;
		}

		return true;
	}

	/**
	 * @return MessageBag
	 */
	public function getErrors() {
		$errors = $this->errors;
		$this->errors = [];
		return $errors;
	}

	/**
	 * @param MessageBag $errors
	 */
	protected function addErrors(MessageBag $errors) {
		if (empty($this->errors)) {
			$this->errors = $errors;
		} else {
			$this->errors->merge($errors);
		}
	}
}