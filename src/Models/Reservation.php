<?php

namespace Models;

use PDO;

class Reservation
{
    private $conn;

    public function __construct()
    {
        // Initialisation de la connexion à la base de données
        $db = new \Config\Database();
        $this->conn = $db-> getConnection();
    }

    // Récupérer les places disponibles pour un spectacle et un horaire donné
    public function getAvailableSeats($spectacleId, $scheduleId)
    {
        try {
            $sql = "SELECT * FROM seats WHERE spectacle_id = ? AND schedule_id = ? AND is_reserved = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spectacleId, $scheduleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching available seats: " . $e->getMessage());
            return false;
        }
    }

    // Réserver une place pour un spectacle et un horaire donnés
    public function reserveSeat($spectacleId, $scheduleId, $seatId)
    {
        try {
            $sql = "UPDATE seats SET is_reserved = 1 WHERE spectacle_id = ? AND schedule_id = ? AND seat_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$spectacleId, $scheduleId, $seatId]);
        } catch (\PDOException $e) {
            error_log("Error reserving seat: " . $e->getMessage());
            return false;
        }
    }
}
