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

    // Récupérer les places disponibles pour un spectacle et un horaire donnés
    public function getAvailableSeats($spectacleId, $scheduleId)
    {
        // Requête SQL pour obtenir les places disponibles
        $query = "SELECT s.id AS seat_id, r.gauge AS room_capacity
                  FROM spectacles_schedule ss
                  INNER JOIN spectacles_room r ON r.theatre_id = ss.spectacle_id
                  WHERE ss.spectacle_id = :spectacleId AND ss.id = :scheduleId AND ss.booked = 0";

        try {
            // Préparation de la requête
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
            $stmt->bindParam(':scheduleId', $scheduleId, PDO::PARAM_INT);
            $stmt->execute();

            // Retourne le résultat sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion ou de requête
            return ['error' => $e->getMessage()];
        }
    }

    // Réserver une place pour un spectacle et un horaire donnés
    public function reserveSeat($spectacleId, $scheduleId, $seatId)
    {
        // Vérification si la place est déjà réservée
        $checkQuery = "SELECT booked FROM spectacles_schedule WHERE id = :scheduleId";
        try {
            $stmt = $this->conn->prepare($checkQuery);
            $stmt->bindParam(':scheduleId', $scheduleId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si la place est déjà réservée, on retourne false
            if ($result['booked'] == 1) {
                return false; // La place est déjà réservée
            }

            // Marquer la place comme réservée
            $updateQuery = "UPDATE spectacles_schedule SET booked = 1 WHERE id = :scheduleId AND spectacle_id = :spectacleId";
            $stmt = $this->conn->prepare($updateQuery);
            $stmt->bindParam(':scheduleId', $scheduleId, PDO::PARAM_INT);
            $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
            $stmt->execute();

            return true; // Réservation réussie
        } catch (PDOException $e) {
            // Gestion des erreurs
            return ['error' => $e->getMessage()];
        }
    }
}
?>
