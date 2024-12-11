<?php
// Page pour afficher les détails du spectacle et gérer les commentaires

require_once 'vendor/autoload.php'; // Si Composer est utilisé, sinon supprime cette ligne.

use Ousmanesacko\SpectacleTest\ReviewController;

// Simule un contrôleur (utiliser ton propre contrôleur réel dans un projet complet)
$reviewController = new ReviewController();

// Gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subscriberId = $_POST['subscriber_id'] ?? 1; // Simule l'utilisateur connecté
    $spectacleId = $_POST['spectacle_id'] ?? 1;   // Simule l'ID du spectacle
    $data = [
        'spectacle_id' => $spectacleId,
        'subscriber_id' => $subscriberId,
        'comment' => trim($_POST['commentaire'] ?? ''),
        'rating' => (int)($_POST['NOTE'] ?? 0)
    ];
    $result = $reviewController->addReview($data);
    if ($result['success']) {
        $successMessage = "Merci pour votre commentaire !";
    } else {
        $errorMessage = "Erreur : " . htmlspecialchars($result['message']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Spectacle</title>
    <style>
        /* Styles CSS intégrés */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f9fc;
            color: #333;
        }

        .header {
            background-color: #007BFF; /* Bleu foncé */
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .spectacle-header {
            text-align: center;
        }

        .spectacle-header img {
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .spectacle-header h2 {
            font-size: 2rem;
            color: #007BFF;
        }

        .spectacle-info h3 {
            color: #007BFF;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }

        .spectacle-info p {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .location iframe {
            width: 100%;
            max-width: 600px;
            height: 400px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .comments {
            margin-top: 30px;
        }

        .comments form {
            display: flex;
            flex-direction: column;
        }

        .comments textarea {
            resize: none;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .comments select {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .comments .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .comments .btn:hover {
            background-color: #0056b3;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Détails du Spectacle</h1>
    </header>

    <div class="container">
        <!-- Image et titre -->
        <div class="spectacle-header">
            <img src="images/spectacle1.jpg" alt="Image du Spectacle" class="spectacle-image">
            <h2>Les étoiles de Paris</h2>
        </div>

        <!-- Informations -->
        <div class="spectacle-info">
            <h3>Informations :</h3>
            <p><strong>Acteurs :</strong> Jean Dupont, Marie Curie</p>
            <p><strong>Metteur en scène :</strong> Pierre Martin</p>
            <p><strong>Horaires :</strong> Du lundi au vendredi, à 19h00</p>
        </div>

        <!-- Messages -->
        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?= htmlspecialchars($successMessage) ?></p>
        <?php elseif (!empty($errorMessage)): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <!-- Carte -->
        <div class="location">
            <h3>Localisation du Théâtre</h3>
            <iframe 
                src="https://www.google.com/maps/embed?pb=YOUR_MAP_LINK_HERE" 
                width="600" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>

        <!-- Commentaires -->
        <div class="comments">
            <h3>Ajouter un commentaire :</h3>
            <form method="POST" action="details_spectacle.php">
                <input type="hidden" name="subscriber_id" value="1">
                <input type="hidden" name="spectacle_id" value="1">
                <label for="commentaire">Votre commentaire :</label><br>
                <textarea id="commentaire" name="commentaire" rows="5" cols="40" required></textarea><br><br>
                <label for="NOTE">Note :</label><br>
                <select id="NOTE" name="NOTE" required>
                    <option value="1">1 - Mauvais</option>
                    <option value="2">2 - Moyen</option>
                    <option value="3">3 - Bon</option>
                    <option value="4">4 - Très Bon</option>
                    <option value="5">5 - Excellent</option>
                </select><br><br>
                <button type="submit" class="btn">Soumettre</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 - Le Spectacles</p>
    </footer>
</body>
</html>
