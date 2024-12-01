<?php
// Inclure la connexion à la base de données
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées par le formulaire
    $user_id = $_POST['user_id']; // Récupérer l'ID utilisateur (normalement via session)
    $spectacle_id = $_POST['spectacle_id']; // ID du spectacle
    $commentaire = trim($_POST['commentaire']); // Le commentaire de l'utilisateur
    $note = (int) $_POST['NOTE']; // Note attribuée (1 à 5)

    if (empty($commentaire) || $note < 1 || $note > 5) {
        echo "Veuillez entrer un commentaire et une note comprise entre 1 et 5.";
        exit;
    }

    try {
        // Insérer les données dans la table `reviews`
        $sql = "INSERT INTO reviews (user_id, spectacle_id, comment, rating) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $spectacle_id, $commentaire, $note]);

        echo "Merci pour votre avis ! Votre commentaire a bien été enregistré.";
    } catch (Exception $e) {
        // Gestion des erreurs
        echo "Erreur lors de l'enregistrement de votre commentaire : " . $e->getMessage();
    }
} else {
    echo "Méthode non autorisée.";
}
?>
