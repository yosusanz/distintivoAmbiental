<?php
	namespace App\Sections\Api\Responses\_Commons;

	use App\Sections\Api\Responses\Response;

	final class CreateResponse extends Response{

		public function __construct() {
			parent::__construct();
		}

		public function createResponse(int $lastInsertedId) {
			$result['lastInsertedId'] = $lastInsertedId ?? 0;
			$this->data = $result;
			unset($result);
			unset($lastInsertedId);

			$this->response['error'] = false;
		}
	}
