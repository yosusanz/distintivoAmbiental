<?php
	namespace App\Sections\Api\DTOs;

	final class _KeyValueDTO{
		public $key;
		public $value;

		public function __construct($entity) {
			$this->key = $entity->key;
			$this->value = $entity->value;
		}
	}