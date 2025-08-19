<?php
	namespace App\Sections\Api\Responses\_Commons;

	use App\Sections\Api\Responses\Response;

	final class UpdateResponse extends Response{

		public function __construct() {
			parent::__construct();
		}

		public function createResponse(int $updated) {
			$result['affectedRows'] = $updated;
			$this->data = $result;
			unset($result);
			unset($updated);

			$this->response['error'] = false;
		}
	}