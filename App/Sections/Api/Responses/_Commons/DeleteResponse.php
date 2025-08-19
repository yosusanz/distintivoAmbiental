<?php
	namespace App\Sections\Api\Responses\_Commons;

	use App\Sections\Api\Responses\Response;

	final class DeleteResponse extends Response{

		public function __construct() {
			parent::__construct();
		}

		public function createResponse(int $deleted){
			$result['hasBeenDeleted'] = (bool)$deleted;
			$this->data = $result;
			unset($result);
			unset($deleted);

			$this->response['error'] = false;
		}
	}
