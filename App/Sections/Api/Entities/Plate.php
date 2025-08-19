<?php
	namespace App\Sections\Api\Entities;

	final class Plate{
		public int $letterPart;
		public int $numberPart;
		public string $letter;
		public int $environmentalCode;
		public string $updatedAt;

		public function __construct() {
		}
	}