<?php
	namespace App\Sections\Api\UseCases;

	use App\Sections\Api\UseCases\UseCase;

	final class _CommonsUseCases extends UseCase {

		public function __construct(string $entity, string $entities) {
			parent::__construct($entity, $entities);
		}


		public function create($input) {
			return $this->service->create($input);
		}

		public function getAll() {
			return $this->service->getAll();
		}

		public function getById($input) {
			return $this->service->getById($input->id);
		}

		public function getList($request) {
			return $this->service->getList($request);
		}

		public function update($input) {
			return $this->service->update($input);
		}

		public function softDeleteById($input) {
			return $this->service->softDeleteById($input->id);
		}

		public function hardDeleteById($input) {
			return $this->service->hardDeleteById($input->id);
		}
	}
