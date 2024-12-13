<?php
// Inclusion du contrôleur
require_once __DIR__ . '/../src/Controllers/ReservationController.php';
use Controllers\ReservationController;

// Vérification du paramètre GET 'id' uniquement
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $spectacleId = (int)$_GET['id']; // Récupération de l'ID du spectacle
} else {
    die("Paramètre 'id' manquant ou invalide.");
}

// Initialisation du contrôleur
$reservationController = new ReservationController();

// Obtenir les places disponibles
$availableSeats = $reservationController->getAvailableSeats($spectacleId, null); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Réservation pour le Spectacle</h1>

        <?php if ($availableSeats['success']): ?>
            <h2>Places disponibles</h2>
            <ul>
                <?php foreach ($availableSeats['data'] as $seat): ?>
                    <li>
                        Place n°<?= htmlspecialchars($seat['seat_number']) ?>
                        <a href="reserve.php?id=<?= $spectacleId ?>&seat_id=<?= htmlspecialchars($seat['id']) ?>">Réserver</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="error"><?= htmlspecialchars($availableSeats['message']) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
