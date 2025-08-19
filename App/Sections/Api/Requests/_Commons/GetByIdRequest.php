<?php
	namespace App\Sections\Api\Requests\_Commons;

	use App\Constants;
	use App\Exceptions\AppError;
	use App\Sections\Api\Helpers\RequestHelper;

	final class GetByIdRequest{

		public int $id;

		public function __construct($id) {
			try {
				$this->id = RequestHelper::checkInputInt($id, 1, Constants::MYSQL_MAX_UBIGINT);
			} catch(TypeError $e) {
				AppError::throw('INVALID_INPUT');
			}
		}
	}