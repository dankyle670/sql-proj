<?php

namespace Models;

use PDO;

class User
{
    private $conn;

    // Constructor: Initialize database connection
    public function __construct()
    {
        $db = new \Config\Database();
        $this->conn = $db->getConnection();
    }

    private function getRoleIdByName($roleName)
    {
        $sql = "SELECT id FROM roles WHERE name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$roleName]);
        return $stmt->fetchColumn();
    }

    // Create a new user
    public function createUser($data)
    {
        $sql = "INSERT INTO spectacles_subscriber (first_name, last_name, username, email, password, birthdate, role_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Map 'role' name to 'role_id'
        $roleId = $this->getRoleIdByName($data['role'] ?? 'Subscriber');

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['email'],
            $data['password'],
            $data['birthdate'],
            $roleId
        ]);
    }

    // Get a user by email
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM spectacles_subscriber WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get a user by ID
    public function getUserById($userId)
    {
        $sql = "SELECT * FROM spectacles_subscriber WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a user's information
    public function updateUser($userId, $data)
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $userId;
        $sql = "UPDATE spectacles_subscriber SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($values);
    }
    
}
