<?php

require_once '../vendor/autoload.php';

use Config\Database;
use Config\TableCreator;

// Initialize Database
if (class_exists(Database::class)) {
    $db = new Database();
    $conn = $db->getConnection();

    echo "<h1>Database Connection Test</h1>";
    try {
        $stmt = $conn->query("SELECT 1");
        if ($stmt) {
            echo "<p>Connected successfully to the database.</p>";
        } else {
            echo "<p>Query execution failed.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage() . "</p>";
    }
    $conn = null;
} else {
    echo "<p>Database class not found!</p>";
}

// Create Tables
if (class_exists(TableCreator::class)) {
    echo "<p>Creating tables...</p>";
    TableCreator::createTables();
} else {
    echo "<p>TableCreator class not found!</p>";
}
?>
