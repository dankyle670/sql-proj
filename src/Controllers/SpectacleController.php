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
        $result = $this->spectacleModel->searchSpectacles($filters);
        if ($result) {
            return ['success' => true, 'data' => $result];
        }
        return ['success' => false, 'message' => 'Aucun spectacle trouvé pour ces critères.'];
    }

    /**
     * Récupérer les spectacles à venir
     * 
     * @return array Les spectacles à venir
     */
    public function getUpcomingSpectacles()
    {
        // Appel de la méthode modèle pour obtenir les spectacles à venir
        $spectacles = $this->spectacleModel->getUpcomingSpectacles();

        if ($spectacles) {
            return ['success' => true, 'data' => $spectacles];
        } else {
            return ['success' => false, 'message' => 'Aucun spectacle à venir trouvé.'];
        }
    }

    /**
     * Récupérer les suggestions de spectacles en fonction du texte saisi.
     *
     * @param string $query Texte saisi par l'utilisateur
     * @return array Résultats des suggestions
     */
    public function getSuggestions($query)
    {
        // Appeler le modèle pour récupérer les suggestions
        $result = $this->spectacleModel->getSuggestions($query);

        if ($result) {
            return ['success' => true, 'data' => $result];
        }

        return ['success' => false, 'message' => 'Aucune suggestion trouvée.'];
    }

    public function getSpectacleById($id) {
        // Instancier le modèle Spectacle
        $spectacleModel = new Spectacle();

        // Appeler la méthode pour récupérer les détails du spectacle depuis la base de données
        return $spectacleModel->getSpectacleById($id); // Cette méthode doit exister dans votre modèle Spectacle
    }

    /**
     * Récupérer les détails d'un spectacle
     * 
     * @param int $spectacleId L'ID du spectacle
     * @return array Détails du spectacle
     */
    public function getSpectacleDetails($spectacleId)
    {
        // Appeler la méthode du modèle pour obtenir les détails du spectacle
        $details = $this->spectacleModel->getSpectacleById($spectacleId);

        if ($details) {
            // Ajouter des informations supplémentaires comme les acteurs et le metteur en scène
            // Assurez-vous que ces méthodes existent dans le modèle
            $actors = $this->spectacleModel->getActorsForSpectacle($spectacleId);
            $director = $this->spectacleModel->getDirectorForSpectacle($spectacleId);

            // Retourner toutes les informations
            return ['success' => true, 'data' => [
                'details' => $details,
                'actors' => $actors,
                'director' => $director
            ]];
        }

        return ['success' => false, 'message' => 'Spectacle introuvable.'];
    }
}
