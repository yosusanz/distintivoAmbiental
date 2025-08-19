<?php
	namespace App\Sections\Web;

	use App\Config;
	use App\Exceptions\AppError;
	
	final class WebController {

		public function __construct($section) {
		}

		public function main() {
			$params = array(
				'title' => Config::APP_TITLE,
				'description' => Config::APP_DESCRIPTION,
				'keywords' => '',
				'css' => array('app/main', 'errors/404')
			);

			$template = '../App/Sections/Web/Views/Public/' . ((isset($_GET['template'])) ? '/' . $_GET['template'] . '/' . basename($_GET['template']) : 'app') . '.php';
			if (!file_exists($template)) {
				$template = '../App/Sections/Web/Views/Errors/404.php';
			}

			require_once $template;
		}
}