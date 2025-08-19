<?php
	namespace App\Exceptions;

	use App\Config;

	require_once '../app/exceptions/appException.php';

	class AppError {
		public static function throw(string $key): void {
			$e = new AppException($key);
			http_response_code($e->httpCode);

			if (Config::IS_PRODUCTION) {
				error_log("API error: {$key} ({$e->getCode()})");

				die();
			} else {
				die($e->getMessage());
			}
		}
	}