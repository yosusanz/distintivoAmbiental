<?php
	namespace App\Sections\Api\Requests\_Commons;

	use App\Exceptions\AppError;
	use App\Sections\Api\Helpers\RequestHelper;

	final class GetListRequest {

		public string $key;
		public string $value;

		public function __construct(string $key, string $value) {
			try {
				$this->key = RequestHelper::checkInputString($key, 1, 255);
				$this->value = RequestHelper::checkInputString($value, 1, 255);
			} catch(TypeError $e) {
				AppError::throw('REQUEST_ERROR');
			}
		}
	}
