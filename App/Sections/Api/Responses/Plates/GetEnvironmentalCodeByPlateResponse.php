<?php
	namespace App\Sections\Api\Responses\Plates;

	use App\Sections\Api\DTOs\Plates\GetEnvironmentalCodeByPlateDTO;
	use App\Sections\Api\Responses\Response;

	final class GetEnvironmentalCodeByPlateResponse extends Response {
		protected $classDTO;

		public function __construct() {
			parent::__construct();
		}

		public function createResponse($request, $data){
			$dto = new GetEnvironmentalCodeByPlateDTO($request->fullPlate);
			$dto->setCode($data);

			$this->data = $dto;
			unset($request);
			unset($data);
			unset($dto);

			$this->response['error'] = false;
		}
	}