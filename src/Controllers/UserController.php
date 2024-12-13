<?php

// Controller to manage users
// - Registration, login, profile updates, etc.
// Responsible: DK (Connection and security), AS (User profile management)

namespace Controllers;

use Models\User;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User(); // Initialize the User model
    }

    // Handle User Registration
    public function register($data)
    {
        // Validate input
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || 
            empty($data['username']) || empty($data['password']) || empty($data['birthdate'])) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        // Check if the email is already registered
        if ($this->userModel->getUserByEmail($data['email'])) {
            return ['success' => false, 'message' => 'This email is already registered.'];
        }

        // Hash the password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // Create the user
        if ($this->userModel->createUser($data)) {
            return ['success' => true, 'message' => 'User registered successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to register user. Please try again.'];
    }

    // Handle User Login
    public function login($data)
    {
        // Validate input
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Email and password are required.'];
        }

        // Fetch user by email
        $user = $this->userModel->getUserByEmail($data['email']);
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }

        // Verify the password
        if (password_verify($data['password'], $user['password'])) {
            // Start session if not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            return ['success' => true, 'message' => 'Login successful.'];
        }

        return ['success' => false, 'message' => 'Invalid email or password.'];
    }

    // Handle User Logout
    public function logout()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        return ['success' => true, 'message' => 'Logout successful.'];
    }

    // Handle Update User Profile
    public function updateProfile($userId, $data)
    {
        if (empty($data)) {
            return ['success' => false, 'message' => 'No data provided for update.'];
        }

        // If password is provided, hash it
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Update user profile
        if ($this->userModel->updateUser($userId, $data)) {
            return ['success' => true, 'message' => 'Profile updated successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to update profile.'];
    }

    // Get User Profile
    public function getProfile($userId)
    {
        $user = $this->userModel->getUserById($userId);
        if ($user) {
            unset($user['password']); // Don't return password
            return ['success' => true, 'data' => $user];
        }

        return ['success' => false, 'message' => 'User not found.'];
    }

    // Check if the user is authenticated
    public function isAuthenticated()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return !empty($_SESSION['user_id']);
    }

    // Get the authenticated user's ID
    public function getAuthenticatedUserId()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_id'] ?? null;
    }
}
?>
