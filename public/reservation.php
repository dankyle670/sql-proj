<?php
// Vérifier que les clés existent dans $_GET
if (isset($_GET['id']) && isset($_GET['schedule_id'])) {
    $spectacleId = $_GET['id'];
    $scheduleId = $_GET['schedule_id'];
} else {
    // Si les paramètres ne sont pas définis, afficher une erreur
    die('Paramètres manquants pour la réservation.');
}

// Initialisation du contrôleur de réservation
require_once '../Controllers/ReservationController.php';
$reservationController = new ReservationController();

// Appel de la méthode getAvailableSeats pour récupérer les places disponibles
$availableSeats = $reservationController->getAvailableSeats($spectacleId, $scheduleId);

if ($availableSeats['success']) {
    // Afficher les places disponibles
    echo "<pre>" . print_r($availableSeats['data'], true) . "</pre>";
} else {
    // Afficher le message d'erreur si aucune place n'est disponible
    echo "Erreur: " . $availableSeats['message'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
</head>
<body>
    <h1>Réservation pour le spectacle</h1>

    <?php if ($availableSeats['success']): ?>
        <h2>Places disponibles</h2>
        <ul>
            <?php foreach ($availableSeats['data'] as $seat): ?>
                <li>
                    Place <?= $seat['seat_number'] ?> 
                    <a href="reserve.php?id=<?= $spectacleId ?>&schedule_id=<?= $scheduleId ?>&seat_id=<?= $seat['seat_id'] ?>">Réserver</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><?= $availableSeats['message'] ?></p>
    <?php endif; ?>
</body>
</html>
