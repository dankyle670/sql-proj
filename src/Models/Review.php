<?php

namespace Models;

use PDO;
use PDOException;

class Review
{
    private $conn;

    public function __construct()
    {
        $db = new \Config\Database();
        $this->conn = $db->getConnection();
    }

    // Add a new review
    public function addReview($spectacleId, $subscriberId, $comment, $rating)
    {
        try {
            $sql = "INSERT INTO reviews (spectacle_id, subscriber_id, comment, rating) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$spectacleId, $subscriberId, $comment, $rating]);
        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage());
            return false;
        }
    }

    // Get all reviews for a spectacle
    public function getReviewsBySpectacle($spectacleId)
    {
        try {
            $sql = "SELECT r.comment, r.rating, s.username AS subscriber_name, r.created_at 
                    FROM reviews r
                    JOIN spectacles_subscriber s ON r.subscriber_id = s.id
                    WHERE r.spectacle_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spectacleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reviews: " . $e->getMessage());
            return false;
        }
    }

    // Delete a review
    public function deleteReview($reviewId)
    {
        try {
            $sql = "DELETE FROM reviews WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$reviewId]);
        } catch (PDOException $e) {
            error_log("Error deleting review: " . $e->getMessage());
            return false;
        }
    }
}
