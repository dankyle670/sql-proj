<?php

require_once '../vendor/autoload.php';

use Controllers\ReviewController;

// Initialize the ReviewController
$reviewController = new ReviewController();

// Validate and fetch the spectacle ID from the URL
if (isset($_GET['spectacle_id']) && is_numeric($_GET['spectacle_id'])) {
    $spectacleId = $_GET['spectacle_id'];
} else {
    echo "ID du spectacle invalide.";
    exit;
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate subscriber ID (replace with session data in production)
    $subscriberId = $_POST['subscriber_id'] ?? 1;

    // Collect and validate form data
    $data = [
        'spectacle_id' => $spectacleId,
        'subscriber_id' => $subscriberId,
        'comment' => trim($_POST['commentaire'] ?? ''),
        'rating' => (int)($_POST['rating'] ?? 0),
    ];

    // Attempt to add the review
    $result = $reviewController->addReview($data);
    if ($result['success']) {
        $message = "<p class='success-message'>Merci pour votre avis !</p>";
        header('Location: spectacle_details.php?id=' . $spectacleId);
        exit;
    } else {
        $message = "<p class='error-message'>Erreur : " . htmlspecialchars($result['message']) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un avis</title>
    <link rel="stylesheet" href="add_review.css">
</head>

<body>
    <header>
        <h1>Ajouter un avis</h1>
    </header>

    <div class="review-form-container">
        <?= $message ?>
        <form method="POST" action="add_review.php?spectacle_id=<?= htmlspecialchars($spectacleId) ?>">
            <!-- Hidden field for the spectacle ID -->
            <input type="hidden" name="spectacle_id" value="<?= htmlspecialchars($spectacleId) ?>">
            <input type="hidden" name="subscriber_id" value="1"> <!-- Replace with dynamic session data -->

            <label for="commentaire">Commentaire :</label><br>
            <textarea name="commentaire" id="commentaire" rows="5" cols="40" required></textarea><br><br>

            <label for="rating">Note :</label><br>
            <select name="rating" id="rating" required>
                <option value="1">1 - Médiocre</option>
                <option value="2">2 - Passable</option>
                <option value="3">3 - Bon</option>
                <option value="4">4 - Très bon</option>
                <option value="5">5 - Excellent</option>
            </select><br><br>

            <button type="submit" class="submit-btn">Envoyer</button>
        </form>
        <a href="spectacle_details.php?id=<?= htmlspecialchars($spectacleId) ?>" class="btn-back">Retour au spectacle</a>
    </div>
</body>

</html>
