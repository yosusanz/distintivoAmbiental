<?php
	namespace App\Sections\Api\Responses\Plates;

	use App\Sections\Api\DTOs\Plates\EnvironmentalCodePlateDTO;
	use App\Sections\Api\Responses\Response;

	final class GetEnvironmentalCodesByImageResponse extends Response {
		protected $classDTO;

		public function __construct() {
			parent::__construct();
		}

		public function createResponse($data){
			is_array($data) ? $this->createResults($data) : $this->createResult($data);
		}

		private function createResult($entity) {
			if (!empty($entity)){
				$this->data = new EnvironmentalCodePlateDTO($entity);
			}
			unset($entity);

			$this->response['error'] = false;
		}

		private function createResults(?array $entities) {
			if (!is_null($entities)){
				foreach ($entities as $entity){
					$this->data[] = new EnvironmentalCodePlateDTO($entity);
				}

				unset($entities);
				unset($entity);
			}

			$this->response['error'] = false;
		}
	}