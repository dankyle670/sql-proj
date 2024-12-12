<?php
<<<<<<< HEAD

// Initialisation du contrôleur de réservation
require_once '../src/Controllers/ReservationController.php';
$ReservationController = new ReservationController();
// Appel de la méthode getAvailableSeats pour récupérer les places disponibles
$availableSeats = $ReservationController->getAvailableSeats($spectacleId, $scheduleId);

if ($availableSeats['success']) {
    // Afficher les places disponibles
    echo "<pre>" . print_r($availableSeats['data'], true) . "</pre>";
} else {
    // Afficher le message d'erreur si aucune place n'est disponible
    echo "Erreur: " . $availableSeats['message'];
}

=======
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
>>>>>>> 770f568a7785a122096dca677ee10b60ba0568be
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
<<<<<<< HEAD
    <h1>Réservation pour legyutdoytdoiytdoyt spectacle</h1>
=======
    <h1>Réservation pour le Spectacle</h1>
>>>>>>> 770f568a7785a122096dca677ee10b60ba0568be

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
