<?php
require_once '../vendor/autoload.php';

use Controllers\UserController;

session_start();

$authController = new UserController();
$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
    ];

    $result = $authController->login($data);

    if ($result['success']) {
        header('Location: home.php');
        exit();
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
