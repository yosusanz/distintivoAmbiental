<?php
	namespace App\Sections\Api\Responses;

	use StdClass;

	abstract class Response {
		protected mixed $data = null;
		protected array $response = [];

		public function __construct() {
			$this->response['error'] = true;
			$this->response['total'] = 0;
			$this->response['data'] = null;
		}

		public function result(): void {
			if (is_null($this->data)) {
				$this->response['total'] = 0;
				$this->response['data'] = new StdClass();
			} else {
				$this->response['total'] = is_array($this->data) ? count($this->data) : 1;
				$this->response['data'] = $this->data;
			}
			unset($this->data);

			header('Content-type: application/json');
			$result = json_encode($this->response);

			exit($result);
		}
	}