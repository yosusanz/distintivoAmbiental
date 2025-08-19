<?php
	namespace App\Sections\Api\Controllers;

	use App\Config;
	use App\Exceptions\AppError;
	use App\Sections\Api\Helpers\ControllerHelper;
	use App\Sections\Api\Helpers\JwtHelper;

	class Controller {
		protected bool $isJwtValid = false;
		protected array $route = [];
		protected array $routes = [];
		protected array $routesNonJwt = [];
		protected $entity;
		protected $entities;

		protected $useCase;

		public function __construct(bool $isMainController = true) {
			$this->exposedRoutes = array_merge($this->exposedRoutes, $this->routesNonJwt);

			if ($isMainController) {
				$this->getCurrentRoute();
			}
		}

		public function main() {
			switch ($this->route['url']) {
				// CRUD
				case isset($this->exposedRoutes['POST:/']) ? $this->exposedRoutes['POST:/'] : null:
					require_once('../App/Sections/Api/Requests/'.$this->entities.'/'.$this->entity.'CreateRequest.php');
					$requestName = '\\App\\Sections\\Api\\Requests\\' . $this->entities . '\\'. $this->entity.'CreateRequest';
					$request = new $requestName();
					unset($requestName);

					$data = $this->create($request);
					unset($request);

					require_once('../App/Sections/Api/Responses/_Commons/CreateResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\CreateResponse();
					$response->createResponse($data);
					unset($data);

					$response->result();
					break;


				case isset($this->exposedRoutes['GET:/']) ? $this->exposedRoutes['GET:/'] : null:
					$data = $this->getAll();

					require_once('../App/Sections/Api/Responses/_Commons/SelectResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\SelectResponse($this->entity);
					$response->createResponse($data);
					unset($data);
		
					$response->result();
					break;

				case isset($this->exposedRoutes['GET:/getById']) ? $this->exposedRoutes['GET:/getById'] : null:
					require_once('../App/Sections/Api/Requests/_Commons/GetByIdRequest.php');
					$request = new \App\Sections\Api\Requests\_Commons\GetByIdRequest($this->route['p0']);

					$data = $this->getById($request);
					unset($request);

					require_once('../App/Sections/Api/Responses/_Commons/SelectResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\SelectResponse($this->entity);
					$response->createResponse($data);
					unset($data);

					$response->result();
					break;

				case isset($this->exposedRoutes['GET:/keyValue']) ? $this->exposedRoutes['GET:/keyValue'] : null:
					require_once('../App/Sections/Api/Requests/_Commons/GetListRequest.php');
					$request = new \App\Sections\Api\Requests\_Commons\GetListRequest($this->route['p0'], $this->route['p1']);

					$data = $this->getList($request);

					require_once('../App/Sections/Api/Responses/_Commons/SelectResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\SelectResponse('_KeyValue');
					$response->createResponse($data);
					unset($data);
		
					$response->result();
					break;


				case isset($this->exposedRoutes['PUT:/updateById']) ? $this->exposedRoutes['PUT:/updateById'] : null:
					require_once('../App/Sections/Api/Requests/'.$this->entities.'/'.$this->entity.'UpdateRequest.php');
					$requestName = '\\App\\Sections\\Api\\Requests\\' . $this->entities . '\\'. $this->entity.'UpdateRequest';
					$request = new $requestName($this->route['p0']);
					unset($requestName);

					$data = $this->update($request);
					unset($request);

					require_once('../App/Sections/Api/Responses/_Commons/UpdateResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\UpdateResponse();
					$response->createResponse($data);
					unset($data);

					$response->result();
					break;

				case isset($this->exposedRoutes['DELETE:/softDeleteById']) ? $this->exposedRoutes['DELETE:/softDeleteById'] : null:
					require_once('../App/Sections/Api/Requests/_Commons/GetByIdRequest.php');
					$request = new \App\Sections\Api\Requests\_Commons\GetByIdRequest($this->route['p0']);

					$data = $this->softDeleteById($request);
					unset($request);

					require_once('../App/Sections/Api/Responses/_Commons/DeleteResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\DeleteResponse($this->entity);
					$response->createResponse($data);
					unset($data);

					$response->result();
					break;

				case isset($this->exposedRoutes['DELETE:/hardDeleteById']) ? $this->exposedRoutes['DELETE:/hardDeleteById'] : null:
					require_once('../App/Sections/Api/Requests/_Commons/GetByIdRequest.php');
					$request = new \App\Sections\Api\Requests\_Commons\GetByIdRequest($this->route['p0']);

					$data = $this->hardDeleteById($request);
					unset($request);

					require_once('../App/Sections/Api/Responses/_Commons/DeleteResponse.php');
					$response = new \App\Sections\Api\Responses\_Commons\DeleteResponse($this->entity);
					$response->createResponse($data);
					unset($data);

					$response->result();
					break;
			}
		}

		private function getCurrentRoute(): void {
			$_GET['params'] ??= '';

			$this->route = ControllerHelper::createRoute($_GET['params'], $this->exposedRoutes);
		}
		private function urlNeedsJwtValidation(): bool {
			return (!(in_array($this->route['url'], $this->routesNonJwt, true)));
		}


		// CRUD
		protected function create($request) {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->create($request);
		}

		protected function getAll() {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->getAll();
		}

		protected function getById($request) {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->getById($request);
		}

		protected function getList($request) {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->getList($request);
		}

		protected function update($request) {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->update($request);
		}

		protected function softDeleteById($request) {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->softDeleteById($request);
		}

		protected function hardDeleteById($request) {
			require_once('../App/Sections/Api/UseCases/_CommonsUseCases.php');
			$this->useCase = new \App\Sections\Api\UseCases\_CommonsUseCases($this->entity, $this->entities);

			return $this->useCase->hardDeleteById($request);
		}
	}