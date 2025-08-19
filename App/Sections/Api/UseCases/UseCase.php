<?php
	namespace App\Sections\Api\UseCases;

	abstract class UseCase {
		private $entity;
		private $entities;
		protected $service;

		public function __construct(string $entity, string $entities) {
			$this->entity = $entity;
			$this->entities = $entities;
			unset($entity);
			unset($entities);

			require_once('../App/Sections/Api/Services/'.$this->entities.'Service.php');

			$serviceName = '\\App\\Sections\\Api\\Services\\' . $this->entities . 'Service';
			$this->service = new $serviceName($this->entity, $this->entities);
			unset($serviceName);
		}
	}