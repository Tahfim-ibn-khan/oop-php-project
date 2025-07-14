<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public function __construct() {
        $this->host     = $_ENV['DB_HOST'];
        $this->port     = $_ENV['DB_PORT'];
        $this->dbname   = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    protected function connect() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "DB connection failed."]);
            exit;
        }
    }
}
