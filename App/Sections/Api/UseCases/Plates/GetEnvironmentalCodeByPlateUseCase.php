<?php
	namespace App\Sections\Api\UseCases\Plates;

	use App\Sections\Api\Helpers\PlatesHelper;
	use App\Sections\Api\Requests\Plates\GetEnvironmentalCodeByPlateRequest;
	use App\Sections\Api\Services\PlatesService;

	final class GetEnvironmentalCodeByPlateUseCase {

		private $entity;
		private $entities;
		protected $service;

		private GetEnvironmentalCodeByPlateRequest $request;
		private int $numericalLettterPart;

		public function __construct(GetEnvironmentalCodeByPlateRequest $request) {
			$this->request = $request;
			unset($request);

			$this->numericalLettterPart = PlatesHelper::lettersToNumber($this->request->letterPart);

			$this->entity = 'Plate';
			$this->entities = 'Plates' . ( (strlen($this->request->letterPart) < 3) ? '0' : substr($this->request->letterPart, 0, 1));

			$this->service = new PlatesService($this->entity, $this->entities);
		}

		public function getEnvironmentalCode() :int {
			$environmentalCode = $this->service->getEnvironmentalCode($this->numericalLettterPart, $this->request->numberPart);
			if ($environmentalCode == 0) {
				$environmentalCode = PlatesHelper::scrapeCodeFromDGT($this->request->fullPlate);

				if ($environmentalCode > 0) {
					$this->service->updatePlateCode($this->numericalLettterPart, $this->request->numberPart, $environmentalCode);
				}
			}

			return $environmentalCode;
		}
	}
