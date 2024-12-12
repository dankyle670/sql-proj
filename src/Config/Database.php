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
            // Connexion à MySQL sans sélectionner de base de données
            $this->pdo = new PDO(
                "mysql:host={$db['host']};charset={$db['charset']}",
                $db['user'],
                $db['password']
            );

            // Vérifier si la base de données existe, sinon la créer
            $result = $this->pdo->query("SHOW DATABASES LIKE '{$db['dbname']}'");
            if ($result->rowCount() === 0) {
                $this->pdo->exec("CREATE DATABASE {$db['dbname']}");
                echo "Base de données créée avec succès.<br>";
            }

            // Connexion à la base de données
            $this->pdo = new PDO(
                "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}",
                $db['user'],
                $db['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die('Échec de la connexion à la base de données: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
