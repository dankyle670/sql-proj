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
$availableSeats = $reservationController->getAvailableSeats($spectacleId, null); // Pas besoin de schedule_id ici
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
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
</body>
</html>
