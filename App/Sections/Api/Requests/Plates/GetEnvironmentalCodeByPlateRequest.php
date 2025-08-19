<?php
	namespace App\Sections\Api\Requests\Plates;

	use App\Exceptions\AppError;

	final class GetEnvironmentalCodeByPlateRequest {

		public string $fullPlate;
		public int $numberPart;
		public string $letterPart;

		public function __construct($plate) {
			$plate = strtoupper($plate);
			if (!preg_match('/^(\d{4})([BCDFGHJKLMNPRSTVWXYZ]{1,3})$/', $plate, $matches)) {
				AppError::throw('INVALID_INPUT');
			}

			$this->fullPlate = $plate;
			$this->numberPart = (int) $matches[1];
			$this->letterPart = $matches[2];
		}
	}