<?php
	namespace App\Sections\Api\Services;

	use App\Sections\Api\Services\Service;

	final class PlatesService extends Service {

		public function __construct(string $entity, string $entities) {
			parent::__construct($entity, $entities);
		}

		// CRUD (overides)

		// CUSTOMS
		public function getEnvironmentalCode(int $letterPart, int $numberPart) {
			$sql = "SELECT environmentalCode FROM {$this->table} 
					WHERE letterPart = :letterPart and numberPart = :numberPart";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute([
				':letterPart' => $letterPart,
				':numberPart'   => $numberPart,
			]);

			return $stmt->fetchColumn();
		}

		public function fetchEnvironmentalCodeFromDgt(string $plate): ?string {
			require_once('../App/Sections/Api/Helpers/EnvironmentalCodeHelper.php');

			return \App\Sections\Api\Helpers\EnvironmentalCodeHelper::scrapeFromDgt($plate);
		}

		public function updatePlateCode(int $letterPart, int $numberPart, int $environmentalCode) {
			$sql = "UPDATE {$this->table} SET 
				environmentalCode=:environmentalCode 
				WHERE letterPart = :letterPart and numberPart = :numberPart";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute([
				':environmentalCode' => $environmentalCode,
				':letterPart' => $letterPart,
				':numberPart' => $numberPart,
			]);
		}
	}