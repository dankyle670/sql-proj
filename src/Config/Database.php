<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/config.php';
        $db = $config['db'];

        try {
            $this->pdo = new PDO(
                "mysql:host={$db['host']};charset={$db['charset']}",
                $db['user'],
                $db['password']
            );

            $result = $this->pdo->query("SHOW DATABASES LIKE '{$db['dbname']}'");
            if ($result->rowCount() === 0) {
                $this->pdo->exec("CREATE DATABASE {$db['dbname']}");
                echo "Database created successfully.<br>";
            }

            $this->pdo = new PDO(
                "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}",
                $db['user'],
                $db['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
