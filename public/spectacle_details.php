<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/SpectacleController.php';
require_once __DIR__ . '/../src/Controllers/ReviewController.php';

use Controllers\SpectacleController;
use Controllers\ReviewController;

// Validate and fetch the spectacle ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID du spectacle invalide.";
    exit;
}

// Initialize controllers
$spectacleController = new SpectacleController();
$reviewController = new ReviewController();

// Fetch spectacle details
$spectacleDetails = $spectacleController->getSpectacleById($id);

if (!$spectacleDetails) {
    echo "Spectacle introuvable.";
    exit;
}

// Fetch reviews for the spectacle
$reviewsResult = $reviewController->getReviewsBySpectacleId($id);
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
    <div class="container">
        <header>
            <h1>Détails du spectacle: <?= htmlspecialchars($spectacleDetails['title'] ?? 'Non spécifié') ?></h1>
        </header>

        <div class="spectacle-details">
            <p><strong>Titre :</strong> <?= htmlspecialchars($spectacleDetails['title'] ?? 'Non spécifié') ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($spectacleDetails['date'] ?? 'Non spécifiée') ?></p>
            <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($spectacleDetails['description'] ?? 'Non spécifiée')) ?></p>
            <p><strong>Lieu :</strong> <?= htmlspecialchars($spectacleDetails['location'] ?? 'Non spécifié') ?></p>
            <p><strong>Durée :</strong> <?= htmlspecialchars($spectacleDetails['duration'] ?? 'Non spécifiée') ?> minutes</p>
            <p><strong>Prix :</strong> <?= htmlspecialchars($spectacleDetails['price'] ?? 'Non spécifié') ?> €</p>
            <p><strong>Type :</strong> <?= htmlspecialchars($spectacleDetails['type'] ?? 'Non spécifié') ?></p>
            <a href="reservation.php?id=<?= htmlspecialchars($id) ?>" class="btn-details">Réservez</a>
            <a href="home.php" class="btn-back">Retour à la liste</a>
        </div>

        <!-- Review Section -->
        <div class="review-section">
            <h2>Avis récents</h2>
            <?php if ($reviewsResult['success']): ?>
                <div class="reviews">
                    <?php foreach (array_slice($reviewsResult['data'], 0, 3) as $review): ?>
                        <div class="review">
                            <p><strong><?= htmlspecialchars($review['subscriber_name'] ?? 'Anonyme'); ?></strong> - <?= htmlspecialchars($review['created_at']); ?></p>
                            <p class="rating">Note: <?= str_repeat('⭐', $review['rating']); ?></p>
                            <p><?= nl2br(htmlspecialchars($review['comment'] ?? '')); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="see-all-reviews">
                    <a href="all_reviews.php?spectacle_id=<?= $id ?>" class="btn-show-all">Voir tous les avis</a>
                </div>
            <?php else: ?>
                <p>Aucun avis disponible pour ce spectacle.</p>
            <?php endif; ?>
            <div class="add-review-btn">
                <a href="add_review.php?spectacle_id=<?= $id ?>" class="btn-add-review">Ajouter un avis</a>
            </div>
        </div>
    </div>
</body>

</html>