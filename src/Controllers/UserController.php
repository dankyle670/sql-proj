<?php

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
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) ||
            empty($data['username']) || empty($data['password']) || empty($data['birthdate'])) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if ($this->userModel->getUserByEmail($data['email'])) {
            return ['success' => false, 'message' => 'This email is already registered.'];
        }

        $data['role'] = $data['role'] ?? 'Subscriber'; // Default to Subscriber
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

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

            // Store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'] ?? 'Subscriber'; // Default role

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
    // Destroy the session
    session_unset();
    session_destroy();
    // Optionally, redirect to home or return a success message
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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return !empty($_SESSION['user_id']);
    }

    // Get the authenticated user's ID
    public function getAuthenticatedUserId()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_id'] ?? null;
    }

    // Get the authenticated user's role
    public function getAuthenticatedUserRole()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_role'] ?? 'Subscriber';
    }
}
