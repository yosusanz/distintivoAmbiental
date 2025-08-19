<?php
	namespace App\Sections\Api\Helpers;

	use App\Exceptions\AppError;
    use App\Sections\Api\Entities\_File;

	final class RequestHelper {
		public static function checkInputInt(?int $value, int $minvalue, int $maxvalue) {
			if (!($value === NULL)) {
				if (filter_var($value, FILTER_VALIDATE_INT) === 0 || filter_var($value, FILTER_VALIDATE_INT)) {
					if (($value < $minvalue) || ($value > $maxvalue)) {
						AppError::throw('INVALID_INPUT');
					}
					return $value;
				} else {
					AppError::throw('INVALID_INPUT');
				}
			} else {
				AppError::throw('INVALID_INPUT');
			}
		}

		public static function checkInputFloat(?float $value, float $minvalue, float $maxvalue) {
			if (!($value === NULL)) {
				if (filter_var($value, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($value, FILTER_VALIDATE_FLOAT)) {
					if (($value < $minvalue) || ($value > $maxvalue)) {
						AppError::throw('INVALID_INPUT');
					}
					return $value;
				} else {
					AppError::throw('INVALID_INPUT');
				}
			} else {
				AppError::throw('INVALID_INPUT');
			}
		}

		public static function checkInputString(?string $value, int $minLength, int $maxLength) {
			if (!($value === NULL)) {
				$value = htmlspecialchars($value);
				if ($minLength + $maxLength > 0) {
					if ((strlen($value) < $minLength) || (strlen($value) > $maxLength)) {
						AppError::throw('INVALID_INPUT');
					}
				}
				return $value;
			} else {
				AppError::throw('INVALID_INPUT');
			}
		}

		public static function checkInputBool(?bool $value) {
			if (!($value === NULL)) {
				if (is_bool($value)) {
					return $value;
				} else {
					AppError::throw('INVALID_INPUT');
				}
			} else {
				AppError::throw('INVALID_INPUT');
			}
		}

		public static function checkInputDate(?string $value) {
			if (!($value === NULL)) {
				$datePattern = '/^\d{4}-\d{2}-\d{2}$/';
				if (preg_match($datePattern, $value)) {
					return DateTime::createFromFormat('Y-m-d', $value);
				} else {
					AppError::throw('INVALID_INPUT');
				}
			} else {
				AppError::throw('INVALID_INPUT');
			}
		}

		public static function checkInputDateTime(?string $value) {
			if (! ($value === NULL)) {
				$datePattern = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/';
				if (preg_match($datePattern, $value)){
					return DateTime::createFromFormat(Constants::DB_DATE_TIME_FORMAT, $value);
				} else {
					AppError::throw('INVALID_INPUT');
				}
			} else {
				AppError::throw('INVALID_INPUT');
			}
		}

		// $dataType: is_string, is_int, is_float, is_bool, is_array, is_null, is_object
		public static function checkInputArrayOf(?array $value, string $dataType) {
			if (is_array($value)) {
				$allItems = array_filter($value, $dataType);
				if (count($allItems) === count($value)) {
					return $value;
				}

				AppError::throw('INVALID_INPUT');
			}
			AppError::throw('INVALID_INPUT');
		}

		public static function getInputFiles(string $inputName = 'files') {
			$filesArray = [];

			if (isset($_FILES[$inputName])) {
				foreach ($_FILES[$inputName]['name'] as $i => $name) {
					if ($_FILES[$inputName]['error'][$i] === UPLOAD_ERR_OK && $_FILES[$inputName]['size'][$i] > 0) {
						$tmpName = $_FILES[$inputName]['tmp_name'][$i];
						$binaryData = file_get_contents($tmpName);

						$filesArray[] = new _File($name, $binaryData);
					}
				}
			}

			return $filesArray;
		}

	}