<?php

require_once __DIR__ . '/../src/Controllers/SpectacleController.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Récupérer les filtres du formulaire
    $filters = [
        'search' => $_GET['search'] ?? '',
        'category_id' => $_GET['category_id'] ?? '',
        'date' => $_GET['date'] ?? ''
    ];

   

    // Créer une instance du contrôleur
    $spectacleController = new SpectacleController();
    // Appeler la méthode pour rechercher les spectacles
    $response = $spectacleController->searchSpectacles($filters);

    // Affichage des résultats
    if ($response['success']) {
        echo "<h2>Résultats de la recherche :</h2>";
        foreach ($response['data'] as $spectacle) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($spectacle['title']) . "</h3>";
            echo "<p>Date: " . htmlspecialchars($spectacle['date']) . "</p>";
            echo "<p>Catégorie: " . htmlspecialchars($spectacle['category_id']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>" . htmlspecialchars($response['message']) . "</p>";
    }
}
?>