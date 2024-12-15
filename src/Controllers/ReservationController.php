<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Reservation.php';

use Models\Reservation;

class ReservationController
{
    private $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation(); // Load the Reservation model
    }

    // Retrieve available seats for a given spectacle
    public function getAvailableSeats($spectacleId)
    {
        try {
            $seats = $this->reservationModel->getAvailableSeats($spectacleId);

            if (isset($seats['error'])) {
                return ['success' => false, 'message' => $seats['error']]; // Database error
            }

            if (!empty($seats)) {
                return ['success' => true, 'data' => $seats]; // Seats available
            }

            return ['success' => false, 'message' => 'Aucune place disponible trouvée.']; // No seats available
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]; // Handle unexpected errors
        }
    }

    // Reserve a seat for a given spectacle and schedule
    public function reserveSeat($userId, $spectacleId, $scheduleId)
{
    try {
        $result = $this->reservationModel->reserveSeat($userId, $spectacleId, $scheduleId);

        if (is_array($result) && isset($result['error'])) {
            return ['success' => false, 'message' => $result['error']]; // Handle database errors
        }

        if ($result) {
            return ['success' => true, 'message' => 'Réservation effectuée avec succès.']; // Successful reservation
        } else {
            return ['success' => false, 'message' => 'Erreur : La place est déjà réservée ou invalide.']; // Reservation failed
        }
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]; // Handle unexpected errors
    }
}

    public function getUserReservations($userId)
    {
        try {
            $reservations = $this->reservationModel->getUserReservations($userId);
            if (isset($reservations['error'])) {
                return ['success' => false, 'message' => $reservations['error']]; // Database error
            }
            if (!empty($reservations)) {
                return ['success' => true, 'data' => $reservations]; // Reservations found
            }
            return ['success' => false, 'message' => 'No reservations found.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur : ' . $e->getMessage()];
        }
    }

}
