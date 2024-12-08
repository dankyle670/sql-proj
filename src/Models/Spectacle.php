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
            $sql = "SELECT * FROM spectacles_spectacle";
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

            if (!empty($filters['title'])) {
                $sql .= " AND title LIKE ?";
                $params[] = '%' . $filters['title'] . '%';
            }

            if (!empty($filters['category_id'])) {
                $sql .= " AND category_id = ?";
                $params[] = $filters['category_id'];
            }

            if (!empty($filters['date'])) {
                $sql .= " AND DATE(date) = ?";
                $params[] = $filters['date'];
            }

            if (!empty($filters['type'])) {
                $sql .= " AND type = ?";
                $params[] = $filters['type'];
            }

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

    /**
     * Récupérer les spectacles à venir
     * 
     * @return array|bool Liste des spectacles à venir ou false en cas d'échec
     */
    public function getUpcomingSpectacles()
    {
        try {
            // Date actuelle
            $currentDate = date('Y-m-d');

            // Requête SQL pour obtenir les spectacles dont la date est supérieure à aujourd'hui
            $sql = "SELECT * FROM spectacles_spectacle WHERE date >= ? ORDER BY date ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$currentDate]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching upcoming spectacles: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Récupérer les spectacles après une certaine date.
     *
     * @param string $date Date de référence (format `Y-m-d`).
     * @return array|bool Liste des spectacles ou `false` en cas d'échec.
     */
    public function getSpectaclesAfterDate($date)
    {
        try {
            $sql = "SELECT * FROM spectacles_spectacle WHERE date >= ? ORDER BY date ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$date]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching upcoming spectacles: " . $e->getMessage());
            return false;
        }
    }


    public function getSuggestions($query)
    {
        try {
            // Effectuer une recherche par titre partiel
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
            // Requête pour obtenir les informations de base du spectacle
            $sql = "SELECT * FROM spectacles_spectacle WHERE id = :spectacleId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':spectacleId', $spectacleId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Vérifier si un spectacle a été trouvé
            $spectacle = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($spectacle) {
                return $spectacle;
            } else {
                return false; // Aucun spectacle trouvé avec cet ID
            }
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
