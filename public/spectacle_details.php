<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/SpectacleController.php';

use Controllers\SpectacleController;

// Vérifier si l'ID est passé en paramètre dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID du spectacle invalide.";
    exit;
}

// Initialisation du contrôleur
$spectacleController = new SpectacleController();

// Récupérer les détails du spectacle
$spectacleDetails = $spectacleController->getSpectacleById($id); // Assurez-vous que cette méthode existe

if (!$spectacleDetails) {
    echo "Spectacle introuvable.";
    exit;
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Spectacle</title>
    <link rel="stylesheet" href="spectacle-details.css">
</head>

<body>
    <header>
        <h1>Details du spectacle: <?= htmlspecialchars($spectacleDetails['title']) ?></h1>
    </header>

    <div class="spectacle-details">
        <p><strong>Titre :</strong> <?= htmlspecialchars($spectacleDetails['title']) ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($spectacleDetails['date']) ?></p>
        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($spectacleDetails['description'])) ?></p>
        <p><strong>Lieu :</strong> <?= htmlspecialchars($spectacleDetails['location']) ?></p>
        <p><strong>Durée :</strong> <?= htmlspecialchars($spectacleDetails['duration']) ?> minutes</p>
        <p><strong>Prix :</strong> <?= htmlspecialchars($spectacleDetails['price']) ?> €</p>
        <p><strong>Type :</strong> <?= htmlspecialchars($spectacleDetails['type']) ?></p>
        <a href="reservation.php?id=<?= htmlspecialchars($id) ?>" class="btn-details">Réservez</a>
        <!-- Lien retour à la liste des spectacles -->
        <a href="home.php" class="btn-back">Retour à la liste</a>
    </div>

  
</body>

</html>
