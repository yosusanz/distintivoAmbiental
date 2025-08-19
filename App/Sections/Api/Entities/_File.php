<?php
	namespace App\Sections\Api\Entities;

	final class _File {
		public string $name;
		public string $binaryData;

		public function __construct(string $name, string $binaryData) {
			$this->name = $name;
			$this->binaryData = $binaryData;
		}
	}