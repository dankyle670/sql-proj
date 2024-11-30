<?php

require_once '../vendor/autoload.php';

use Config\Database;
use Config\TableCreator;
use Config\FillDatabase;

echo "<h1>Application Initialization</h1>";

// Initialize Database

if (class_exists(Database::class)) {
    $db = new Database();
    $conn = $db->getConnection();

    echo "<h2>Database Connection Test</h2>";
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
    echo "<h2>Creating Tables</h2>";
    TableCreator::createTables();
} else {
    echo "<p>TableCreator class not found!</p>";
}

// Route Handling
echo "<h2>Handling Routes</h2>";

// Check for route in the query parameter
//$route = $_GET['route'] ?? 'home';
//
//switch ($route) {
//    case 'home':
//        require_once 'home.php'; // Adjust path if necessary
//        break;
//
//    case 'spectacle':
//        require_once 'spectacle_details.php'; // Display a spectacle's details
//        break;
//
//    case 'profile':
//        require_once 'profile.php'; // User profile management
//        break;
//
//    case 'reservation':
//        require_once 'reservation.php'; // Reservation page
//        break;
//
//    case 'add_review':
//        require_once 'add_review.php'; // Add review functionality
//        break;
//
//    case 'fill_database':
//        if (class_exists(FillDatabase::class)) {
//            FillDatabase::fillTables();
//            echo "<p>Database filled with fake data.</p>";
//        } else {
//            echo "<p>FillDatabase class not found!</p>";
//        }
//        break;
//
//    default:
//        echo "<h3>404 - Page not found</h3>";
//        break;
//}
//
echo "<h2>Setup Complete</h2>";
?>
