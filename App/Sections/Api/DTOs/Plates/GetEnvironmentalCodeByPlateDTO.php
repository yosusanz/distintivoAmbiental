<?php
	namespace App\Sections\Api\DTOs\Plates;

	final class GetEnvironmentalCodeByPlateDTO{
		public string $plate;
		public ?string $code;
		public string $description;

		public function __construct(string $plate) {
			$this->plate = $plate;
		}

		public function setCode(int $code) {
			switch ($code) {
				case 0:
					$this->code = null;
					$this->description = 'Sin distintivo';

					break;
				case 1:
					$this->code = 'B';
					$this->description = 'B (gasolina 2000-2005 / diésel 2006-2013)';
					
					break;
				case 2:
					$this->code = 'C';
					$this->description = 'C (gasolina desde 2006 / diésel desde 2014)';
					
					break;
				case 3:
					$this->code = 'ECO';
					$this->description = 'ECO (híbridos, GNC, GLP)';
					
					break;
				case 4:
					$this->code = '0';
					$this->description = 'CERO (eléctricos, híbridos enchufables)';
					
					break;
				case 99:
					$this->code = null;
					$this->description = 'Desconocido por la DGT';

					break;
			}
		}
	}