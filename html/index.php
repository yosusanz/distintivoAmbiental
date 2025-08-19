<?php
	spl_autoload_register('autoLoad');

	use App\Config;
	use App\Constants;
	use App\Exceptions\AppError;

	if (Config::IS_PRODUCTION) { error_reporting(0); } else {register_shutdown_function('reportFatalError');}

	runApp();


	function autoLoad($class) {
		$prefix = 'App\\';
		$base_dir = __DIR__ . '/../app/';

		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			return;
		}

		$relative_class = substr($class, $len);
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

		if (file_exists($file)) {
			require_once $file;
		}
	}

	function getSection(): string{
		$section = $_GET['section'] ?? 'web';
		unset($_GET['section']);

		if (!preg_match('/^[a-zA-Z0-9_]+$/', $section)) {
			$section = 'web';
		}
		if (!file_exists('../app/sections/'.$section)) {
			AppError::throw('NO_SECTION');
		}

		return $section;
	}

	function reportFatalError() {
		$info = error_get_last();

		if (!$info) return;

		if ($info['type'] !== E_ERROR) return;

		echo "<b>ER-NRO</b>: " . $info['type'] . "\n<br/>" .
			"<b>ER-MSG</b>: " . $info['message'] . "\n<br/>" .
			"<b>FILE</b>: " . $info['file'] . "\n<br/>" .
			"<b>LINE #</b>: " . $info['line'] . "\n<br/>" .
			"<pre>" . htmlentities(var_export(debug_backtrace(), true)) . "</pre>";
	}

	function runApp() {
		$section = getSection();

		$controllerClass = "App\\Sections\\$section\\" . ucfirst($section) . "Controller";
		if (!class_exists($controllerClass)) {
			AppError::throw('NO_CONTROLLER');
		}

		$controller = new $controllerClass($section);
		if (!method_exists($controller, 'main')) {
			AppError::throw('NO_METHOD');
		}

		$controller->main();
	}