<?php
	namespace App\Sections\Api;

	use App\Config;
	use App\Constants;
	use App\Exceptions\AppError;

	final class ApiController {
		private $method;

		public function __construct($method) {
			$this->method = $method;

			$this->checkApiKey();
		}

		public function main(): void {
			$model = $_GET['model'];

			if (Config::IS_OBFUSCATED) {
				if (defined("App\\Sections\\Api\\RoutesObfuscated::{$model}")) {
					$model = constant("App\\Sections\\Api\\RoutesObfuscated::{$model}");
				} else {
					AppError::throw('NO_CONTROLLER');
				}
			}

			$class = "App\\Sections\\Api\\Controllers\\".$model."Controller";
			if (!class_exists($class)) {
				AppError::throw('NO_CONTROLLER');
			}

			$controller = new $class();
			if (!method_exists($controller, 'main')) {
				AppError::throw('NO_METHOD');
			}

			$controller->main();
		}

		private function checkApiKey(): void {
			$model = $_GET['model'] ?? null;
			if ($model) {
				$key = $_SERVER['HTTP_API_KEY'] ?? null;

				if ($key && $key === Config::KEY_API) {
					return;
				}

				AppError::throw($key ? 'BAD_APIKEY' : 'MISSING_ARGUMENTS');
			} else {
				$response = new \App\Sections\Api\Responses\AppVersionResponse;

				$response->result();
			}
		}
	}