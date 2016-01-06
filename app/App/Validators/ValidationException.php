<?php  namespace Logstats\App\Validators;

use Exception;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception {

	/**
	 * Validation errors
	 */
	private $errors;


	/**
	 * @param MessageBag $errors
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct(MessageBag $errors = null, $message = "", $code = 0, Exception $previous = null) {
		$this->errors = $errors;

		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return MessageBag
	 */
	public function getErrors() {
		return $this->errors;
	}
}