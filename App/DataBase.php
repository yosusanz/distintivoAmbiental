<?php
	namespace App;

	use PDO;
	use Exception;
	use App\Config;
	use App\Exceptions\AppError;

	final class Database {
		public PDO $connection;
	
		public function __construct()
		{
			try {
				$options = [
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => false,
					PDO::MYSQL_ATTR_FOUND_ROWS   => true,
				];

				$dsn = sprintf(
					'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
					Config::DB_HOSTNAME,
					Config::DB_PORT,
					Config::DB_NAME
				);

				$this->connection = new PDO(
					$dsn,
					Config::DB_USER,
					Config::DB_PASS,
					$options
				);
			} catch (Exception $e) {
				throw $e;
			}
		}
	}