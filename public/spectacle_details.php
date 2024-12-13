<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/SpectacleController.php';
require_once __DIR__ . '/../src/Controllers/ReviewController.php';

use Controllers\SpectacleController;
use Controllers\ReviewController;

// Vérifier si l'ID est passé en paramètre dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID du spectacle invalide.";
    exit;
}

// Initialisation des contrôleurs
$spectacleController = new SpectacleController();
$reviewController = new ReviewController();

// Récupérer les détails du spectacle
$spectacleDetails = $spectacleController->getSpectacleById($id);

if (!$spectacleDetails) {
    echo "Spectacle introuvable.";
    exit;
}

// Récupérer les avis pour ce spectacle (limiter à 5 derniers avis)
$reviewsResult = $reviewController->getReviews($id);
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
        <h1>Détails du spectacle: <?= htmlspecialchars($spectacleDetails['title']) ?></h1>
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

<!-- Section des avis -->
<div class="review-section">
    <h2>Avis récents</h2>

    <?php if ($reviewsResult['success']): ?>
        <!-- Afficher les 3 derniers avis -->
        <?php $recentReviews = array_slice($reviewsResult['data'], 0, 3); ?>
        <div class="reviews">
            <?php foreach ($recentReviews as $review): ?>
                <div class="review">
                    <p><strong><?= htmlspecialchars($review['subscriber_name']); ?></strong> - <?= htmlspecialchars($review['created_at']); ?></p>
                    <p class="rating">Note: <?= str_repeat('⭐', $review['rating']); ?></p>
                    <p><?= nl2br(htmlspecialchars($review['comment'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bouton pour voir tous les avis -->
        <div class="see-all-reviews">
            <a href="all_reviews.php?spectacle_id=<?= $id ?>" class="btn-show-all">Voir tous les avis</a>
        </div>
    <?php else: ?>
        <p>Aucun avis disponible pour ce spectacle.</p>
    <?php endif; ?>

    <!-- Bouton pour ajouter un avis -->
    <div class="add-review-btn">
        <a href="add_review.php?spectacle_id=<?= $id ?>" class="btn-add-review">Ajouter un avis</a>
    </div>
</div>

</body>

</html>