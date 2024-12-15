<?php

namespace Controllers;

use Models\Spectacle;

class SpectacleController
{
    private $spectacleModel;

    public function __construct()
    {
        // Initialisation du modèle Spectacle
        $this->spectacleModel = new Spectacle();
    }

    /**
     * Récupérer tous les spectacles
     */
    public function getAllSpectacles()
    {
        $spectacles = $this->spectacleModel->getAllSpectacles();
        if ($spectacles) {
            return ['success' => true, 'data' => $spectacles];
        } else {
            return ['success' => false, 'message' => 'Aucun spectacle trouvé.'];
        }
    }

    /**
     * Recherche des spectacles par filtres
     */
    public function searchSpectacles($filters)
    {
        
        error_log("Filtres reçus : " . print_r($filters, true));

        
        $result = $this->spectacleModel->searchSpectacles($filters);

        
        error_log("Résultats de la recherche : " . print_r($result, true));

        
        if ($result) {
            return ['success' => true, 'data' => $result];
        }

        return ['success' => false, 'message' => 'Aucun spectacle trouvé pour ces critères.'];
    }

    /**
     * Récupérer les spectacles à venir
     */
    public function getUpcomingSpectacles()
    {
        $spectacles = $this->spectacleModel->getUpcomingSpectacles();

        if ($spectacles) {
            return ['success' => true, 'data' => $spectacles];
        } else {
            return ['success' => false, 'message' => 'Aucun spectacle à venir trouvé.'];
        }
    }

    /**
     * Récupérer les suggestions de spectacles en fonction du texte saisi.
     */
    public function getSuggestions($query)
    {
        
        error_log("Recherche de suggestions pour : " . $query);

        
        $result = $this->spectacleModel->getSuggestions($query);

        
        error_log("Suggestions trouvées : " . print_r($result, true));

        if ($result) {
            return ['success' => true, 'data' => $result];
        }

        return ['success' => false, 'message' => 'Aucune suggestion trouvée.'];
    }

    public function getSpectacleById($id)
    {
        return $this->spectacleModel->getSpectacleById($id);
    }

    /**
     * Récupérer les détails d'un spectacle
     */
    public function getSpectacleDetails($spectacleId)
    {
        $details = $this->spectacleModel->getSpectacleById($spectacleId);

        if ($details) {
            $actors = $this->spectacleModel->getActorsForSpectacle($spectacleId);
            $director = $this->spectacleModel->getDirectorForSpectacle($spectacleId);

            return ['success' => true, 'data' => [
                'details' => $details,
                'actors' => $actors,
                'director' => $director
            ]];
        }

        return ['success' => false, 'message' => 'Spectacle introuvable.'];
    }
}
