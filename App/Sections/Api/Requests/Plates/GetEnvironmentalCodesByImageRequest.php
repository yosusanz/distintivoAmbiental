<?php
	namespace App\Sections\Api\Requests\Plates;

	use App\Exceptions\AppError;
	use App\Sections\Api\Entities\_File;
	use App\Sections\Api\Helpers\RequestHelper;

	final class GetEnvironmentalCodesByImageRequest {
		public _File $image;

		public function __construct() {
			try {
				$this->image = RequestHelper::getInputFiles()[0];
			} catch (TypeError $e) {
				AppError::throw('REQUEST_ERROR');
			}
		}
	}
