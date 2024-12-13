<?php

namespace Controllers;

use PDO;
use Exception;

class ProfileController
{
    private $conn;

    // Constructor: Initialize database connection
    public function __construct()
    {
        $db = new \Config\Database();
        $this->conn = $db->getConnection();
    }

    // Fetch user profile data
    public function getProfile($subscriberId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT first_name, last_name, email, username, birthdate, password FROM spectacles_subscriber WHERE id = ?");
            $stmt->execute([$subscriberId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    // Update user profile data
    public function updateProfile($data)
    {
        try {
            $stmt = $this->conn->prepare("
                UPDATE spectacles_subscriber
                SET first_name = ?, last_name = ?, email = ?, username = ?, birthdate = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['username'],
                $data['birthdate'],
                $data['id']
            ]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Change user password
    public function changePassword($subscriberId, $currentPassword, $newPassword)
    {
        try {
            // Fetch the current password hash from the database
            $stmt = $this->conn->prepare("SELECT password FROM spectacles_subscriber WHERE id = ?");
            $stmt->execute([$subscriberId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['success' => false, 'message' => 'User not found.'];
            }

            // Verify the current password
            if (!password_verify($currentPassword, $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect.'];
            }

            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password in the database
            $updateStmt = $this->conn->prepare("UPDATE spectacles_subscriber SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $subscriberId]);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
