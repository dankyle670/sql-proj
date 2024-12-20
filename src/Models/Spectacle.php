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
            $sql = "SELECT id, title, synopsis, image FROM spectacles_spectacle"; 
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
            $sql = "SELECT * FROM spectacles_spectacle WHERE 1=1"; 
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
            $sql = "
                SELECT s.id, s.title, s.synopsis, ss.day AS date, s.image 
                FROM spectacles_spectacle s
                JOIN spectacles_schedule ss ON s.id = ss.spectacle_id
                WHERE ss.day > NOW()
                ORDER BY ss.day ASC
                LIMIT 4";
            $stmt = $this->conn->prepare($sql);
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
            $sql = "SELECT id, title FROM spectacles_spectacle WHERE title LIKE ? LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching suggestions: " . $e->getMessage());
            return false;
        }
    }

    //mod get spectaclebyid

    public function getSpectacleById($spectacleId)
    {
        $sql = "
            SELECT
                s.id,
                s.title,
                s.synopsis AS description,
                s.duration,
                s.price,
                ss.day AS date,
                t.address AS location,
                c.name AS category
            FROM spectacles_spectacle s
            LEFT JOIN spectacles_schedule ss ON s.id = ss.spectacle_id
            LEFT JOIN spectacles_room r ON r.id = ss.id
            LEFT JOIN spectacles_theatre t ON r.theatre_id = t.id
            LEFT JOIN spectacles_category c ON s.category_id = c.id
            WHERE s.id = :spectacleId
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
