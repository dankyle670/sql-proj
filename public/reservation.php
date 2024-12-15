<?php
require_once __DIR__ . '/../src/Controllers/ReservationController.php';
use Controllers\ReservationController;

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Initialize the ReservationController
$reservationController = new ReservationController();

// Check if spectacle_id is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Paramètre 'id' manquant ou invalide.");
}

$spectacleId = (int)$_GET['id'];
$subscriberId = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle reservation request
if (isset($_GET['schedule_id']) && is_numeric($_GET['schedule_id'])) {
    $scheduleId = (int)$_GET['schedule_id'];

    // Attempt to reserve the seat
    $reservationResult = $reservationController->reserveSeat($subscriberId, $spectacleId, $scheduleId);

    if ($reservationResult['success']) {
        // Show success message
        $message = "<p style='color: green;'>Réservation réussie !</p>";
    } else {
        // Show error message
        $message = "<p style='color: red;'>Erreur lors de la réservation : " . htmlspecialchars($reservationResult['message']) . "</p>";
    }
}

// Fetch available seats for the given spectacle
$availableSeats = $reservationController->getAvailableSeats($spectacleId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="reservation.css">
</head>
<body>
    <div class="container">
        <h1>Réservation pour le Spectacle</h1>

        <!-- Display messages -->
        <?php if (isset($message)) echo $message; ?>

        <!-- Display available seats -->
        <?php if ($availableSeats['success']): ?>
            <h2>Places disponibles</h2>
            <p>Nombre de places disponibles : <?= count($availableSeats['data']) ?></p>
            <ul>
                <?php foreach ($availableSeats['data'] as $seat): ?>
                    <?php if (isset($seat['schedule_id'], $seat['day'], $seat['booked'])): ?>
                        <li>
                            <strong>Horaire :</strong> <?= htmlspecialchars($seat['day']) ?> <br>
                            <strong>Statut :</strong> <?= $seat['booked'] ? 'Réservée' : 'Disponible' ?> <br>
                            <?php if (!$seat['booked']): ?>
                                <a href="reservation.php?id=<?= $spectacleId ?>&schedule_id=<?= $seat['schedule_id'] ?>"
                                   class="btn">Réserver</a>
                            <?php else: ?>
                                <span class="btn disabled">Indisponible</span>
                            <?php endif; ?>
                        </li>
                    <?php else: ?>
                        <li class="error-message">Données de place manquantes.</li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="error"><?= htmlspecialchars($availableSeats['message']) ?></p>
        <?php endif; ?>

        <a href="home.php" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>
