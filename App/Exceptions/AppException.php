<?php
	namespace App\Exceptions;

	class AppException extends \Exception{
		public int $httpCode;

		public function __construct(string $key) {
			$map = [
				'NO_SECTION' => [1, 400],					// No existe la sección solicitada
				'NO_CONTROLLER' => [2, 400],				// No existe el controlador solicitado
				'NO_METHOD' => [3, 400],					// No existe el método solicitado
				'NO_PERMISSION' => [4, 403],				// No tiene permiso de acceso
				'NOT_IMPLEMENTED' => [5, 501],				// Endpoint no implementado
				'BAD_APIKEY' => [6, 401],					// API key no válida
				'LOGIN_ERROR' => [7, 401],					// Error al hacer login
				'REQUEST_ERROR' => [8, 409],				// Error al procesr el request
				'INVALID_INPUT' => [9, 400],				// Datos de entrada no válidos
				'COMMAND_ERROR' => [10, 409],				// Error al ejecutar el comando
				'MISSING_ARGUMENTS' => [11, 400],			// Faltan argumentos en el request
				'GONE' => [12, 410],						// No existe el objeto seleccionado
				'INCOMPATIBLE_ARGUMENTS' => [13, 406],		// Argumentos incompatibles en el request
				'UNKNOW' => [999, 409],						// No se conoce el error
			];

			[$code, $httpCode] = $map[$key] ?? [999, 409];
			$this->httpCode = $httpCode;

			parent::__construct($key, $code);
		}
	}

