<?php
	namespace App\Sections\Api\Helpers;

	use DOMDocument;
	use DOMXPath;
    use App\Sections\Api\Helpers\HtmlHelper;

	final class PlatesHelper {
		
		private static array $validLetters = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'];
		private const DGT_BASE_URL = 'https://sede.dgt.gob.es/es/vehiculos/informacion-de-vehiculos/distintivo-ambiental/index.html?matricula=';

		public static function lettersToNumber(string $letters): int {
			$letters = strtoupper($letters);
			$base = count(self::$validLetters);
			$result = 0;

			foreach (str_split($letters) as $char) {
				$pos = array_search($char, self::$validLetters);
				if ($pos === false) return -1;
				$result = $result * $base + ($pos + 1);
			}

			return $result;
		}

		public static function numberToLetters(int $code): string {
			$base = count(self::$validLetters);
			if ($code <= 0) return '';

			$letters = '';
			while ($code > 0) {
				$rem = ($code - 1) % $base;
				$letters = self::$validLetters[$rem] . $letters;
				$code = intdiv($code - 1, $base);
			}

			return $letters;
		}

		public static function scrapeCodeFromDGT(string $plate) {
			$html = HtmlHelper::getHTML(self::DGT_BASE_URL.$plate);

			return self::extractEnvironmentalCode($html);
		}

		private static function extractEnvironmentalCode(string $html): int {
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);

			$doc->loadHTML($html);
			libxml_clear_errors();

			$xpath = new DOMXPath($doc);
			$nodes = $xpath->query('//p[contains(@class, "my-auto")]');

			foreach ($nodes as $node) {
				$text = $node->textContent;

				if (stripos($text, 'no se ha encontrado ningún resultado') !== false) {
					return 99;
				}
				
				if (preg_match('/Distintivo Ambiental (\w+)/i', $text, $matches)) {
					switch (strtoupper($matches[1])) {
						case 'B':   return 1;
						case 'C':   return 2;
						case 'ECO': return 3;
						case '0':   return 4;
					}
				}
			}

			return 0;
		}
	}