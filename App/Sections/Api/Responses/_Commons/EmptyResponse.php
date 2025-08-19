<?php
	namespace App\Sections\Api\Responses\_Commons;

	use App\Sections\Api\Responses\Response;

	final class EmptyResponse extends Response {
		public function __construct() {
			parent::__construct();
		}

		public function createResponse() {
			$this->response['error'] = false;
		}
	}
