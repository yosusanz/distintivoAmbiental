<?php
	namespace App\Sections\Api\UseCases\Plates;

	use App\Config;
	use App\Sections\Api\Requests\Plates\GetEnvironmentalCodesByImageRequest;

	final class GetPlatesFromImageUseCase {

		private GetEnvironmentalCodesByImageRequest $request;

		public function __construct(GetEnvironmentalCodesByImageRequest $request) {
			$this->request = $request;
			unset($request);
		}

		public function getPlatesFromImage() : array {
			$fileName = $this->saveImageFromRequest();

			$pythonOutput = '';

			exec("python3 /var/www/App/python/extractor/main.py $fileName", $pythonOutput, $exitCode);
			
			$pythonJson = implode("", $pythonOutput);
			unset($pythonOutput);

			$plates = json_decode($pythonJson, true);
			if (!is_array($plates)) {
				return [];
			}

			$minConfidence = Config::MIN_CONFIDENCE_VALUE;

			$plates = array_filter($plates, function ($p) use ($minConfidence) {
				$hasText = isset($p['plate']) && trim($p['plate']) !== '';
				$hasConfidence = isset($p['confidence']) && (float)$p['confidence'] >= $minConfidence;

				return $hasText && $hasConfidence;
			});

			return array_values($plates);
		}

		private function saveImageFromRequest() : string {
			$uploadDir = '/var/www/html/images/uploads/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0775, true);
			}

			$fileName = $uploadDir . time() . ".jpg";
			$file = $this->request->image;

			file_put_contents($fileName, $file->binaryData);

			return $fileName;
		}
	}
