<?php

namespace app\core;

use PDO;
use PDOException;

class Database
{

    public PDO $pdo;
    private string $dsn = "mysql:host=localhost;port=3306;dbname=login_23;charset=utf8";
    public static $db;

    public function __construct()
    {
        try {
            $this->pdo = new PDO($this->dsn, "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Could not establish connection to database: " . $e->getMessage();
        }

        self::$db = $this;
    }

    public function query(string $sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
