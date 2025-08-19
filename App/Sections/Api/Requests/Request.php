<?php
	namespace App\Sections\Api\Requests;

	abstract class Request {
		protected ?string $inputData = null;

		public function __construct() {
			$this->inputData = file_get_contents("php://input");

			unset($rawInput);
		}
	}