<?php

namespace Models;

require_once __DIR__ . '/../Config/Database.php';

use PDO;
use PDOException;

class Reservation
{
    private $conn;

    public function __construct()
    {
        $db = new \Config\Database();
        $this->conn = $db->getConnection();
    }

    // Retrieve available seats for a given spectacle
    public function getAvailableSeats($spectacleId)
    {
        $query = "
            SELECT ss.id AS schedule_id, ss.day, ss.booked
            FROM spectacles_schedule ss
            WHERE ss.spectacle_id = :spectacleId AND ss.booked = 0
        ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
            $stmt->execute();

            $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$seats) {
                return ['error' => 'Aucune place disponible trouvée.'];
            }

            return $seats;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Reserve a seat for a given schedule and spectacle
    public function reserveSeat($userId, $spectacleId, $scheduleId)
{
    try {
        // Vérifier si la place est déjà réservée
        $checkQuery = "
            SELECT booked 
            FROM spectacles_schedule 
            WHERE id = :scheduleId 
              AND spectacle_id = :spectacleId
        ";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(':scheduleId', $scheduleId, PDO::PARAM_INT);
        $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || $result['booked'] == 1) {
            return ['error' => 'Cette place est déjà réservée ou invalide.'];
        }

        // Marquer la place comme réservée
        $updateQuery = "
            UPDATE spectacles_schedule 
            SET booked = 1, subscriber_id = :userId 
            WHERE id = :scheduleId 
              AND spectacle_id = :spectacleId
        ";
        $stmt = $this->conn->prepare($updateQuery);
        $stmt->bindParam(':scheduleId', $scheduleId, PDO::PARAM_INT);
        $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true; // Réservation réussie
        }

        return ['error' => 'Impossible de réserver la place.'];
    } catch (PDOException $e) {
        return ['error' => 'Erreur SQL : ' . $e->getMessage()];
    }
}

    public function getUserReservations($userId)
    {
        $query = "
            SELECT s.title, ss.day, ss.booked
            FROM spectacles_schedule ss
            INNER JOIN spectacles_spectacle s ON ss.spectacle_id = s.id
            WHERE ss.subscriber_id = :userId
        ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
