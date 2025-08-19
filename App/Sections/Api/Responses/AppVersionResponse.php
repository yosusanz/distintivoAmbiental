<?php
    namespace App\Sections\Api\Responses;

    use App\Config;

    final class AppVersionResponse{
        protected array $response;

        public function __construct() {
            $this->response['version'] = Config::APP_VERSION;
        }

        public function result(){
            header('Content-type: application/json');
            echo json_encode($this->response);

            exit();
        }
    }