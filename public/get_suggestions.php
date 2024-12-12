<?php
require_once '../vendor/autoload.php';

use Controllers\SpectacleController;

// Initialisation du contrôleur
$spectacleController = new SpectacleController();

// Récupération de la saisie utilisateur
$query = $_GET['query'] ?? '';

// Obtenir les suggestions
$suggestions = $spectacleController->getSuggestions($query);

// Retourner les suggestions en JSON
header('Content-Type: application/json');
echo json_encode($suggestions);