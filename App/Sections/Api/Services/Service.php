<?php
	namespace App\Sections\Api\Services;

	use App\DataBase;
	use PDO;


    abstract class Service{
		protected $connection;
		protected $className;
		protected $table;

		public function __construct(string $entity, string $entities) {
			$db = new DataBase;
			$this->connection = $db->connection;

			$this->className = '\\App\\Sections\\Api\\Entities\\' . $entity;
			$this->table = $entities;
		}

		// CRUD
		public function create($input) {
			$arrayInput = (array) $input;
			$columns = implode(', ', array_keys($arrayInput));
			$placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($arrayInput)));

			$sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
			$stmt = $this->connection->prepare($sql);

			foreach ($arrayInput as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}

			$stmt->execute();

			return $this->connection->lastInsertId();
		}

		public function getAll() {
			$where = $this->hasPropertyIsDeleted() ? ' WHERE isDeleted = 0' : '';
			$sql = "SELECT * FROM {$this->table}{$where}";
			$stmt = $this->connection->query($sql);

			return $stmt->fetchAll(PDO::FETCH_CLASS, $this->className) ?: null;
		}

		public function getById(int $id) {
			$where = $this->hasPropertyIsDeleted() ? ' AND isDeleted = 0' : '';
			$sql = "SELECT * FROM {$this->table} WHERE id = :id{$where}";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute(['id' => $id]);

			return $stmt->fetchObject($this->className) ?: null;
		}

		public function getList($request) {
			require_once('../App/Sections/Api/Entities/_KeyValue.php');

			$where = $this->hasPropertyIsDeleted() ? ' WHERE isDeleted = 0' : '';
			$sql = "SELECT `{$request->key}` as `key`, `{$request->value}` as `value` FROM {$this->table}{$where}";
			$stmt = $this->connection->query($sql);

			return $stmt->fetchAll(PDO::FETCH_CLASS, '\App\Sections\Api\Entities\_KeyValue') ?: null;
		}

		public function update($input) {
			$arrayInput = (array) $input;
			$id = $arrayInput['id'];
			unset($arrayInput['id']);

			$set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($arrayInput)));

			$sql = "UPDATE {$this->table} SET $set WHERE id = :id";
			$stmt = $this->connection->prepare($sql);

			foreach ($arrayInput as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);

			$stmt->execute();

			return $stmt->rowCount();
		}

		public function softDeleteById(int $id) {
			$sql = "UPDATE {$this->table} SET isDeleted = 1 WHERE id = :id";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute(['id' => $id]);

			return $stmt->rowCount();
		}

		public function hardDeleteById(int $id) {
			$sql = "DELETE FROM {$this->table} WHERE id = :id";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute(['id' => $id]);

			return $stmt->rowCount();
		}

		protected function hasPropertyIsDeleted(): bool {
			return property_exists(new $this->className, 'isDeleted');
		}
    }