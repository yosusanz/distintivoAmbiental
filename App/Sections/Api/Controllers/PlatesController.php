<?php
	namespace App\Sections\Api\Controllers;

	use App\Config;
	use App\Exceptions\AppError;

	final class PlatesController extends Controller {

		public function __construct($isMainController = true) {
			$this->entity = 'Plate';
			$this->entities = 'Plates';

			if ($isMainController){
				$this->exposedRoutes = array(
					'GET:/getEnvironmentalCodeByPlate' => 'GET:/{p0}',
					'POST:/getEnvironmentalCodesByImage' => 'POST:/'.((Config::IS_OBFUSCATED) ? '9cd41f8f68aeb6c8d368093c4114213a' : 'getEnvironmentalCodesByImage'),
				);
				$this->routesNonJwt = array(
				);
			}

			parent::__construct($isMainController);
		}

		public function main(){
			switch ($this->route['url']) {
				// CUSTOMS
				case $this->exposedRoutes['GET:/getEnvironmentalCodeByPlate']:
					require_once('../App/Sections/Api/Requests/Plates/GetEnvironmentalCodeByPlateRequest.php');
					$request = new \App\Sections\Api\Requests\Plates\GetEnvironmentalCodeByPlateRequest($this->route['p0']);

					$data = $this->getEnvironmentalCodeByPlate($request);

					require_once('../App/Sections/Api/Responses/Plates/GetEnvironmentalCodeByPlateResponse.php');
					$response = new \App\Sections\Api\Responses\Plates\GetEnvironmentalCodeByPlateResponse();
					$response->createResponse($request, $data);
					unset($request);
					unset($data);

					$response->result();
					break;

				case $this->exposedRoutes['POST:/getEnvironmentalCodesByImage']:
					require_once('../App/Sections/Api/Requests/Plates/GetEnvironmentalCodesByImageRequest.php');
					$request = new \App\Sections\Api\Requests\Plates\GetEnvironmentalCodesByImageRequest();

					$data = $this->getEnvironmentalCodesByImage($request);
					unset($request);

					require_once('../App/Sections/Api/Responses/Plates/GetEnvironmentalCodesByImageResponse.php');
					$response = new \App\Sections\Api\Responses\Plates\GetEnvironmentalCodesByImageResponse();
					$response->createResponse($data);
					unset($data);

					$response->result();
					break;
			}

			parent::main();

			AppError::throw('NOT_IMPLEMENTED');
		}

		protected function getEnvironmentalCodeByPlate($request) {
			require_once('../App/Sections/Api/UseCases/Plates/GetEnvironmentalCodeByPlateUseCase.php');
			$useCase = new \App\Sections\Api\UseCases\Plates\GetEnvironmentalCodeByPlateUseCase($request);

			return $useCase->getEnvironmentalCode();
		}

		protected function getEnvironmentalCodesByImage($request) {
			require_once('../App/Sections/Api/UseCases/Plates/GetPlatesFromImageUseCase.php');
			$useCase = new \App\Sections\Api\UseCases\Plates\GetPlatesFromImageUseCase($request);

			$plates = $useCase->getPlatesFromImage();
			unset($useCase);

			require_once('../App/Sections/Api/Requests/Plates/GetEnvironmentalCodeByPlateRequest.php');
			foreach ($plates as &$plate) {
				$request = new \App\Sections\Api\Requests\Plates\GetEnvironmentalCodeByPlateRequest($plate['plate']);
				$plate['environmentalCode'] = $this->getEnvironmentalCodeByPlate($request);
			}
			unset($plate);
			unset($request);

			return $plates;
		}

	}