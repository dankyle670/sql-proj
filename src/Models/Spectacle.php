<?php

namespace Models;

use PDO;
use PDOException;

class Spectacle
{
    private $conn;

    public function __construct()
    {
        $db = new \Config\Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Récupérer tous les spectacles
     */
    public function getAllSpectacles()
    {
        try {
            $sql = "SELECT * FROM spectacles_spectacle LIMIT 6";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching all spectacles: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Recherche des spectacles par filtres
     */
    public function searchSpectacles($filters)
    {
        try {
            $sql = "SELECT * FROM spectacles_spectacle WHERE 1=1"; // On commence avec une condition qui est toujours vraie
            $params = [];

            // Log de la requête SQL avant l'exécution
            error_log("SQL avant filtrage : " . $sql);

            // Filtre par titre
            if (!empty($filters['search'])) {
                $sql .= " AND title LIKE ?";
                $params[] = '%' . $filters['search'] . '%';
            }

            // Filtre par catégorie
            if (!empty($filters['category_id'])) {
                $sql .= " AND category_id = ?";
                $params[] = $filters['category_id'];
            }

            // Filtre par date
            if (!empty($filters['date'])) {
                $sql .= " AND DATE(date) = ?";
                $params[] = $filters['date'];
            }

            // Log des filtres appliqués
            error_log("SQL après filtrage : " . $sql);
            error_log("Paramètres SQL : " . print_r($params, true));

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching spectacles: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les spectacles à venir
     */
    public function getUpcomingSpectacles()
    {
        try {
            $query = "SELECT * FROM spectacles_schedule WHERE day > NOW() ORDER BY day ASC LIMIT 4";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching upcoming spectacles: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer les suggestions de spectacles
     */
    public function getSuggestions($query)
    {
        try {
            // Log de la requête avant exécution
            error_log("SQL pour suggestions : SELECT id, title FROM spectacles_spectacle WHERE title LIKE '%$query%' LIMIT 10");

            $sql = "SELECT id, title FROM spectacles_spectacle WHERE title LIKE ? LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['%' . $query . '%']);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching suggestions: " . $e->getMessage());
            return false;
        }
    }

    public function getSpectacleById($spectacleId)
    {
        try {
            $sql = "SELECT * FROM spectacles_spectacle WHERE id = :spectacleId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching spectacle by ID: " . $e->getMessage());
            return false;
        }
    }

    
    public function getAvailableSeats($spectacleId, $scheduleId)
    {
        try {
            $sql = "SELECT * FROM seats WHERE spectacle_id = ? AND schedule_id = ? AND reserved = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spectacleId, $scheduleId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching available seats: " . $e->getMessage());
            return false;
        }
    }

    public function getActorsForSpectacle($spectacleId)
    {
        try {
            $sql = "SELECT * FROM actors WHERE spectacle_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spectacleId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching actors: " . $e->getMessage());
            return false;
        }
    }

    public function getDirectorForSpectacle($spectacleId)
    {
        try {
            $sql = "SELECT * FROM directors WHERE spectacle_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spectacleId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching director: " . $e->getMessage());
            return false;
        }
    }


    public function reserveSeat($spectacleId, $scheduleId, $seatId)
    {
        try {
            $sql = "UPDATE seats SET reserved = 1 WHERE spectacle_id = ? AND schedule_id = ? AND seat_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$spectacleId, $scheduleId, $seatId]);
        } catch (PDOException $e) {
            error_log("Error reserving seat: " . $e->getMessage());
            return false;
        }
    }
}


