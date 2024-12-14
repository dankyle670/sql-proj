<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/ReviewController.php';

use Controllers\ReviewController;

// Vérifier si l'ID du spectacle est passé en paramètre dans l'URL
if (isset($_GET['spectacle_id']) && is_numeric($_GET['spectacle_id'])) {
    $spectacleId = $_GET['spectacle_id'];
} else {
    echo "ID du spectacle invalide.";
    exit;
}

// Initialisation du contrôleur
$reviewController = new ReviewController();

// Récupérer tous les avis pour ce spectacle
$reviewsResult = $reviewController->getReviews($spectacleId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les avis</title>
</head>
<body>
    <h1>Avis pour ce spectacle</h1>

    <?php if ($reviewsResult['success']): ?>
        <?php foreach ($reviewsResult['data'] as $review): ?>
            <div class="review">
                <p><strong><?= htmlspecialchars($review['subscriber_name']); ?></strong> - <?= htmlspecialchars($review['created_at']); ?></p>
                <p>Note: <?= htmlspecialchars($review['rating']); ?>/5</p>
                <p><?= nl2br(htmlspecialchars($review['comment'])); ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun avis disponible pour ce spectacle.</p>
    <?php endif; ?>

    <a href="spectacle_details.php?id=<?= $spectacleId ?>">Retour aux détails du spectacle</a>
</body>
</html>