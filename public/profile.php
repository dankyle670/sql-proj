<?php

require_once '../vendor/autoload.php';

use Controllers\ProfileController;
use Controllers\UserController;
use Controllers\ReservationController;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize controllers
$profileController = new ProfileController();
$userController = new UserController();
$reservationController = new ReservationController();

// Restrict access to authenticated users
if (!$userController->isAuthenticated()) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Get the authenticated user's ID
$subscriberId = $userController->getAuthenticatedUserId();

if (!$subscriberId) {
    echo "<p style='color:red;'>You must be logged in to view this page.</p>";
    exit();
}

// Handle logout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $logoutResult = $userController->logout();
    if ($logoutResult['success']) {
        header('Location: login.php'); // Redirect to login page
        exit();
    }
}

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $data = [
        'id' => $subscriberId,
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'username' => trim($_POST['username'] ?? ''),
        'birthdate' => trim($_POST['birthdate'] ?? '')
    ];

    $result = $profileController->updateProfile($data);
    if ($result['success']) {
        echo "<p style='color:green;'>Profile updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . htmlspecialchars($result['message']) . "</p>";
    }
}

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($newPassword !== $confirmPassword) {
        echo "<p style='color:red;'>Error: New passwords do not match.</p>";
    } else {
        $result = $profileController->changePassword($subscriberId, $currentPassword, $newPassword);
        if ($result['success']) {
            echo "<p style='color:green;'>Password changed successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . htmlspecialchars($result['message']) . "</p>";
        }
    }
}

// Fetch user profile data
$profile = $profileController->getProfile($subscriberId);
if (!$profile) {
    echo "<p style='color:red;'>Error fetching profile data.</p>";
    exit();
}

// Fetch user's reservations
$reservations = $reservationController->getUserReservations($subscriberId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>

        <!-- Back to Home Button -->
        <a href="home.php" class="btn-back-home">Retour à l'accueil</a>

        <!-- Logout Button -->
        <form method="POST" action="profile.php" class="logout-form">
            <button type="submit" name="logout" class="logout-button">Déconnexion</button>
        </form>

        <?php
        if (isset($result)) {
            if ($result['success']) {
                echo "<p class='success-message'>" . htmlspecialchars($result['message']) . "</p>";
            } else {
                echo "<p class='error-message'>" . htmlspecialchars($result['message']) . "</p>";
            }
        }
        ?>

        <!-- Profile Update Form -->
        <form method="POST" action="profile.php">
            <input type="hidden" name="update_profile" value="1">

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($profile['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($profile['last_name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($profile['email']); ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($profile['username']); ?>" required>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($profile['birthdate']); ?>">

            <button type="submit">Update Profile</button>
        </form>

        <h2>Change Password</h2>
        <form method="POST" action="profile.php">
            <input type="hidden" name="change_password" value="1">

            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Change Password</button>
        </form>

        <h2>My Reservations</h2>
        <?php if (!empty($reservations['data'])): ?>
            <ul>
                <?php foreach ($reservations['data'] as $reservation): ?>
                    <li>
                        <strong>Spectacle:</strong> <?= htmlspecialchars($reservation['title']); ?><br>
                        <strong>Date:</strong> <?= htmlspecialchars($reservation['day']); ?><br>
                        <strong>Statut:</strong> <?= $reservation['booked'] ? 'Réservée' : 'Disponible'; ?><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="error-message">Aucune réservation trouvée.</p>
        <?php endif; ?>
    </div>
</body>
</html>
