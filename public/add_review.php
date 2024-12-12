<?php

require_once '../vendor/autoload.php';

use Controllers\ReviewController;

// Initialiser le contrôleur ReviewController
$reviewController = new ReviewController();

// Récupérer l'ID du spectacle depuis l'URL
$spectacleId = $_GET['spectacle_id'] ?? 1;  // Remplace 1 par une valeur dynamique si nécessaire

// Gérer la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simuler les valeurs de subscriber_id et spectacle_id
    $subscriberId = $_POST['subscriber_id'] ?? 1; // Remplace par les données de session ou dynamique en production

    // Collecter et valider les données du formulaire
    $data = [
        'spectacle_id' => $spectacleId,
        'subscriber_id' => $subscriberId,
        'comment' => trim($_POST['commentaire'] ?? ''),
        'rating' => (int)($_POST['NOTE'] ?? 0)
    ];

    // Tenter d'ajouter l'avis
    $result = $reviewController->addReview($data);
    if ($result['success']) {
        echo "<p style='color:green;'>Merci pour votre avis !</p>";
        // Rediriger vers la page des détails du spectacle
        header('Location: spectacle_details.php?id=' . $spectacleId);
        exit;
    } else {
        echo "<p style='color:red;'>Erreur : " . htmlspecialchars($result['message']) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un avis</title>
</head>
<body>
    <h1>Ajouter un avis</h1>
    <form method="POST" action="add_review.php?spectacle_id=<?= htmlspecialchars($spectacleId) ?>">
        <!-- Champ caché pour l'ID du spectacle -->
        <input type="hidden" name="spectacle_id" value="<?= $spectacleId ?>">

        <label for="commentaire">Commentaire :</label><br>
        <textarea name="commentaire" id="commentaire" rows="5" cols="40" required></textarea><br><br>

        <label for="NOTE">Note :</label><br>
        <select name="NOTE" id="NOTE" required>
            <option value="1">1 - Médiocre</option>
            <option value="2">2 - Passable</option>
            <option value="3">3 - Bon</option>
            <option value="4">4 - Très bon</option>
            <option value="5">5 - Excellent</option>
        </select><br><br>

        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
