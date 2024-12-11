<?php

namespace Controllers;

use Models\Reservation;

class ReservationController
{
    private $reservationModel; // On utilise Reservation et non Spectacle

    public function __construct()
    {
        // Initialisation du modèle de réservation (assurez-vous que ce modèle existe)
        $this->reservationModel = new ReservationController(); // Le modèle de réservation
    }

    // Récupérer les places disponibles pour un spectacle et une programmation donnée
    public function getAvailableSeats($spectacleId, $scheduleId)
    {
        // Appel de la méthode getAvailableSeats dans le modèle Reservation
        $seats = $this->reservationModel->getAvailableSeats($spectacleId, $scheduleId);
        return ['success' => true, 'data' => $seats];
    }

    // Réserver une place pour un spectacle et une programmation donnés
    public function reserveSeat($spectacleId, $scheduleId, $seatId)
    {
        // Appel de la méthode reserveSeat dans le modèle Reservation
        $result = $this->reservationModel->reserveSeat($spectacleId, $scheduleId, $seatId);
        if ($result) {
            return ['success' => true, 'message' => 'Réservation effectuée avec succès.'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de la réservation.'];
        }
    }
}
