<?php
// Récupérer l'ID du spectacle à partir de l'URL
$spectacleId = $_GET['id'];
$spectacleDetails = $spectacleController->getSpectacleDetails($spectacleId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Spectacle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Détails du Spectacle</h1>

    <?php if ($spectacleDetails['success']): ?>
        <h2><?= htmlspecialchars($spectacleDetails['data']['details']['title']) ?></h2>
        <p><?= htmlspecialchars($spectacleDetails['data']['details']['description']) ?></p>
        
        <h3>Acteurs</h3>
        <ul>
            <?php foreach ($spectacleDetails['data']['actors'] as $actor): ?>
                <li><?= htmlspecialchars($actor['name']) ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Metteur en scène</h3>
        <p><?= htmlspecialchars($spectacleDetails['data']['director']['name']) ?></p>

        <h3>Horaires</h3>
        <ul>
            <?php foreach ($spectacleDetails['data']['details']['schedules'] as $schedule): ?>
                <li><?= htmlspecialchars($schedule['date']) ?> - <?= htmlspecialchars($schedule['time']) ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Ajouter un Commentaire</h3>
        <form action="submit_comment.php" method="POST">
            <textarea name="comment" placeholder="Votre commentaire"></textarea>
            <button type="submit">Envoyer</button>
        </form>

        <!-- Carte géolocalisée du théâtre -->
        <h3>Localisation du Théâtre</h3>
        <div id="map"></div>

    <?php else: ?>
        <p><?= $spectacleDetails['message'] ?></p>
    <?php endif; ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var mapOptions = {
                center: { lat: 48.8566, lng: 2.3522 }, // Exemple : Paris
                zoom: 12
            };
            var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        }
    </script>
</body>
</html>
