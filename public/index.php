<?php

require_once '../vendor/autoload.php';

use Config\Database;
use Config\TableCreator;
use Config\FillDatabase;
use Controllers\UserController;

echo "<h1>Application Initialization</h1>";

// Initialize Database

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