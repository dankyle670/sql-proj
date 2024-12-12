<?php

require_once '../vendor/autoload.php';

use Config\Database;
use Config\TableCreator;
use Config\FillDatabase;
use Controllers\UserController;

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

// Test UserController
if (class_exists(UserController::class)) {
    echo "<h2>Testing UserController</h2>";

    $userController = new UserController();

    // Test Registration
    echo "<h3>Registering User</h3>";
    $registrationResult = $userController->register([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'TestUser',
        'email' => 'testuser@example.com',
        'password' => 'securepassword',
        'birthdate' => '1990-01-01'
    ]);
    echo $registrationResult['message'] . "<br>";

    // Test Login
    echo "<h3>Logging in User</h3>";
    $loginResult = $userController->login([
        'email' => 'testuser@example.com',
        'password' => 'securepassword',
    ]);
    echo $loginResult['message'] . "<br>";

    // Test Fetching User Profile
    if ($loginResult['success']) {
        session_start();
        $userId = $_SESSION['user_id']; // Assuming session stores the user ID

        echo "<h3>Fetching User Profile</h3>";
        $profileResult = $userController->getProfile($userId);
        if ($profileResult['success']) {
            echo "User Profile:<br>";
            echo "<pre>" . print_r($profileResult['data'], true) . "</pre>";
        } else {
            echo $profileResult['message'] . "<br>";
        }
    } else {
        echo "Skipping profile fetch test due to login failure.<br>";
    }

    // Test Updating User Profile
    echo "<h3>Updating User Profile</h3>";
    $updateResult = $userController->updateProfile(1, [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'username' => 'UpdatedTestUser',
        'password' => 'newsecurepassword',
        'birthdate' => '1995-05-15'
    ]);
    echo $updateResult['message'] . "<br>";

    // Test Logout
    echo "<h3>Logging out User</h3>";
    $logoutResult = $userController->logout();
    echo $logoutResult['message'] . "<br>";
} else {
    echo "<p>UserController class not found!</p>";
}

// Add a link to go to the home page
echo '<a href="home.php" style="
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    font-family: Arial, sans-serif;
    transition: background-color 0.3s ease;
"
onmouseover="this.style.backgroundColor=\'#0056b3\';"
onmouseout="this.style.backgroundColor=\'#007BFF\';"
>Aller à la page d\'accueil</a>';

echo "<h2>Setup Complete</h2>";
?>
