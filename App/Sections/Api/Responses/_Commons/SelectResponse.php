<?php
	namespace App\Sections\Api\Responses\_Commons;

	use App\Sections\Api\Responses\Response;

	final class SelectResponse extends Response {
		protected $classDTO;

		public function __construct(string $dto) {
			parent::__construct();

			$this->classDTO = '\\App\\Sections\\Api\\DTOs\\' . $dto . 'DTO';

			require_once('../App/Sections/Api/DTOs/'.$dto.'DTO.php');
		}

		public function createResponse($data){
			is_array($data) ? $this->createResults($data) : $this->createResult($data);
		}

		private function createResult($entity) {
			if (!empty($entity)){
				$this->data = new $this->classDTO($entity);
			}
			unset($entity);

			$this->response['error'] = false;
		}

		private function createResults(?array $entities) {
			if (!is_null($entities)){
				foreach ($entities as $entity){
					$this->data[] = new $this->classDTO($entity);
				}

				unset($entities);
				unset($entity);
			}

			$this->response['error'] = false;
		}
	}
