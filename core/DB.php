<?php

namespace app\core;

use PDO;
use PDOException;

class DB
{
    public PDO $pdo;
    public static $db;

    private function __construct()
    {
        $dsn = 'mysql:host=localhost;port=3306;dbname=login_23;charset=utf8';
        $username = 'root';
        $password = '';
        try {
            $this->pdo = new PDO($dsn, $username, $password);
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
