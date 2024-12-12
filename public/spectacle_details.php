<?php
// Connexion à la base de données
require_once 'includes/database.php';

// Récupérer l'ID du spectacle depuis l'URL
$spectacleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Vérifier si l'ID est valide
if ($spectacleId <= 0) {
    die("Spectacle non valide !");
}

// Préparer et exécuter la requête SQL
$query = $db->prepare("
    SELECT 
        s.id AS spectacle_id,
        s.name AS spectacle_name,
        s.description AS spectacle_description,
        t.name AS theatre_name,
        t.address AS theatre_address,
        t.phone AS theatre_phone,
        t.email AS theatre_email
    FROM 
        spectacles_spectacle s
    JOIN 
        spectacles_theatre t
    ON 
        s.theatre_id = t.id
    WHERE 
        s.id = :spectacle_id
");
$query->execute(['spectacle_id' => $spectacleId]);
$spectacle = $query->fetch();

// Vérifier si le spectacle existe
if (!$spectacle) {
    die("Spectacle introuvable !");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($spectacle['spectacle_name']); ?></title>
    <style>
        /* Styles intégrés ici */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f9fc;
            color: #333;
        }
        .header {
            background-color: #007BFF;
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
        <h1><?php echo htmlspecialchars($spectacle['spectacle_name']); ?></h1>
    </header>

    <div class="container">
        <h2>À propos du spectacle</h2>
        <p><?php echo htmlspecialchars($spectacle['spectacle_description']); ?></p>

        <h2>Informations sur le théâtre</h2>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($spectacle['theatre_name']); ?></p>
        <p><strong>Adresse :</strong> <?php echo htmlspecialchars($spectacle['theatre_address']); ?></p>
        <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($spectacle['theatre_phone']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($spectacle['theatre_email']); ?></p>
    </div>

    <footer class="footer">
        <p>&copy; 2024 - Mon Projet de Spectacles</p>
    </footer>
</body>
</html>
