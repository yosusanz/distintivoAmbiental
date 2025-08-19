<?php
	namespace App;

	final class Config {

		// Este es un archivo de ejemplo. Rellena con tus datos reales.
		// En caso de modificar el KEY_API, deberá modificarse el mismo valor en /html/js/Config.js

		public const APP_NAME = 'ENVIRONMENTAL_CODES_SERVER';
		public const APP_VERSION = '1.0.0.0';
		public const APP_TITLE = 'Environmental Codes';
		public const APP_DESCRIPTION = 'Aplicación para la obtención de distintivos ambientales de la DGT mediante Deep Learning';

		public const DB_HOSTNAME = '<introduce el hostname aquí>';
		public const DB_PORT = 3306;
		public const DB_NAME = '<introduce el nombre de la base de datos aquí>';
		public const DB_USER = '<introduce el usuario aquí>';
		public const DB_PASS = '<introduce el password aquí>';

		public const IS_PRODUCTION = true;
		public const IS_OBFUSCATED = true;

		public const KEY_API = 'bd1fe17550bc9d319f02c0cb6c86da619b7158a8dcf06f66e86ce0f53e24acfbc61806770400d0d0045ccc34c7f042f7';

		public const MIN_CONFIDENCE_VALUE = 0.20;
	}
