<?php
	namespace App\Sections\Api\DTOs\Plates;

	final class EnvironmentalCodePlateDTO {
		public int $plateIndex;
		public string $plate;
		public string $plateImage;
		public float $confidence;
		public ?string $environmentalCode;
		public string $description;

		public function __construct($plate) {
			$this->plateIndex = $plate['plateIndex'];
			$this->plate = $plate['plate'];
			$this->plateImage = $plate['plateImage'];
			$this->confidence = $plate['confidence'];
			
			$this->setCode($plate['environmentalCode']);
		}

		public function setCode(int $code) {
			switch ($code) {
				case 0:
					$this->environmentalCode = null;
					$this->description = 'Sin distintivo';

					break;
				case 1:
					$this->environmentalCode = 'B';
					$this->description = 'B (gasolina 2000-2005 / diésel 2006-2013)';
					
					break;
				case 2:
					$this->environmentalCode = 'C';
					$this->description = 'C (gasolina desde 2006 / diésel desde 2014)';
					
					break;
				case 3:
					$this->environmentalCode = 'ECO';
					$this->description = 'ECO (híbridos, GNC, GLP)';
					
					break;
				case 4:
					$this->environmentalCode = '0';
					$this->description = 'CERO (eléctricos, híbridos enchufables)';
					
					break;
				case 99:
					$this->environmentalCode = null;
					$this->description = 'Desconocido por la DGT';

					break;
			}
		}
	}