<?php

namespace CloudSystems\CloudTranscribe\Exceptions;


class ValidationException extends BaseException
{


	/**
	 * The array of errors.
	 *
	 * @var array
	 *
	 */
	public $errors;


	/**
	 * Create a new exception instance.
	 *
	 * @return void
	 *
	 */
	public function __construct(array $errors)
	{
		parent::__construct('The given data failed validation.');
		$this->errors = $errors;
	}


	/**
	 * The array of errors.
	 *
	 * @return array
	 *
	 */
	public function errors()
	{
		return $this->errors;
	}


}
