<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Reservation.php';

use Models\Reservation;

class ReservationController
{
    private $reservationModel;

    public function __construct()
    {
        // Charger le modèle Reservation
        $this->reservationModel = new Reservation();
    }

    // Récupérer les places disponibles pour un spectacle et un horaire donné
    public function getAvailableSeats($spectacleId, $scheduleId)
    {
        $seats = $this->reservationModel->getAvailableSeats($spectacleId, $scheduleId);
        if ($seats) {
            return ['success' => true, 'data' => $seats];
        } else {
            return ['success' => false, 'message' => 'Aucune place disponible trouvée.'];
        }
    }

    // Réserver une place pour un spectacle et un horaire donnés
    public function reserveSeat($spectacleId, $scheduleId, $seatId)
    {
        $result = $this->reservationModel->reserveSeat($spectacleId, $scheduleId, $seatId);
        if ($result) {
            return ['success' => true, 'message' => 'Réservation effectuée avec succès.'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de la réservation ou la place est déjà réservée.'];
        }
    }
}
?>
