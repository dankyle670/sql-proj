<?php

require_once '../vendor/autoload.php';

use Controllers\ProfileController;
use Controllers\UserController;

// Initialize controllers
$profileController = new ProfileController();
$userController = new UserController();

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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>

    <!-- Logout Button -->
    <form method="POST" action="profile.php">
        <button type="submit" name="logout">Logout</button>
    </form>

    <!-- Profile Update Form -->
    <form method="POST" action="profile.php">
        <input type="hidden" name="update_profile" value="1">

        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>" required><br><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required><br><br>

        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($profile['username']); ?>" required><br><br>

        <label for="birthdate">Birthdate:</label><br>
        <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($profile['birthdate']); ?>"><br><br>

        <button type="submit">Update Profile</button>
    </form>

    <h2>Change Password</h2>
    <!-- Password Change Form -->
    <form method="POST" action="profile.php">
        <input type="hidden" name="change_password" value="1">

        <label for="current_password">Current Password:</label><br>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">Change Password</button>
    </form>
</body>
</html>
