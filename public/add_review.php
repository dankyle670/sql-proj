<?php



require_once '../vendor/autoload.php';

use Controllers\ReviewController;

// Initialize the ReviewController
$reviewController = new ReviewController();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate session or dynamic values for subscriber_id and spectacle_id
    $subscriberId = $_POST['subscriber_id'] ?? 1; // Replace with session data in production
    $spectacleId = $_POST['spectacle_id'] ?? 1;   // Replace with dynamic data in production

    // Collect and validate form data
    $data = [
        'spectacle_id' => $spectacleId,
        'subscriber_id' => $subscriberId,
        'comment' => trim($_POST['commentaire'] ?? ''),
        'rating' => (int)($_POST['NOTE'] ?? 0)
    ];

    // Attempt to add the review
    $result = $reviewController->addReview($data);
    if ($result['success']) {
        echo "<p style='color:green;'>Thank you for your review!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . htmlspecialchars($result['message']) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
</head>
<body>
    <h1>Add a Review</h1>
    <form method="POST" action="add_review.php">
        <!-- Hidden fields for spectacle and subscriber IDs -->
        <input type="hidden" name="subscriber_id" value="1"> <!-- Replace with session -->
        <input type="hidden" name="spectacle_id" value="1"> <!-- Replace with dynamic data -->

        <label for="commentaire">Comment:</label><br>
        <textarea id="commentaire" name="commentaire" rows="5" cols="40" required></textarea><br><br>

        <label for="NOTE">Rating:</label><br>
        <select id="NOTE" name="NOTE" required>
            <option value="1">1 - Poor</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Very Good</option>
            <option value="5">5 - Excellent</option>
        </select><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
