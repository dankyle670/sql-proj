<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/SpectacleController.php';

use Controllers\SpectacleController;

// Initialisation du contrôleur
$spectacleController = new SpectacleController();

// Gestion de la recherche
$filters = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['title'])) {
        $filters['title'] = $_GET['title'];
    }
    if (!empty($_GET['category_id'])) {
        $filters['category_id'] = $_GET['category_id'];
    }
    if (!empty($_GET['date'])) {
        $filters['date'] = $_GET['date'];
    }
    if (!empty($_GET['type'])) {
        $filters['type'] = $_GET['type'];
    }
}

// Recherche des spectacles
$searchResults = $spectacleController->searchSpectacles($filters);

// Récupérer les spectacles à venir
$upcomingSpectacles = $spectacleController->getUpcomingSpectacles();

// Récupérer tous les spectacles
$spectacles = $spectacleController->getAllSpectacles();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- En-tête -->
    <header>
        <h1>Bienvenue sur notre site de spectacles</h1>
        <div class="auth-buttons">
            <a href="login.php" class="btn">Login</a>
            <a href="signup.php" class="btn">Signup</a>
        </div>
        <div class="menu-burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="nav-menu">
            <a href="login.php" class="btn">Login</a>
            <a href="signup.php" class="btn">Signup</a>
        </div>
    </header>

    <!-- Contenu principal -->
    <div class="container">
        <!-- Moteur de recherche -->
        <section>
            <h2>Rechercher un spectacle</h2>
            <form id="search-form" method="GET" action="spectacle_details.php">
                <div class="search-container">
                    <input
                        type="text"
                        id="search-input"
                        name="query"
                        placeholder="Rechercher un spectacle..."
                        autocomplete="on" />
                    <ul id="suggestions" class="suggestions-list"></ul>
                </div>
                <button type="submit" class="btn">Rechercher</button>
            </form>
        </section>

        <!-- Spectacles à venir -->
        <section>
            <h2>Spectacles à venir</h2>
            <?php if ($upcomingSpectacles['success']): ?>
                <div class="spectacles-grid">
                    <?php foreach ($upcomingSpectacles['data'] as $spectacle): ?>
                        <div class="spectacle-card">
                            <h2><?= htmlspecialchars($spectacle['title']) ?></h2>
                            <p><strong>Date :</strong> <?= htmlspecialchars($spectacle['date']) ?></p>
                            <p><strong>Description :</strong> <?= htmlspecialchars($spectacle['synopsis'] ?? 'Non spécifié') ?></p>
                            <a href="spectacle_details.php?id=<?= htmlspecialchars($spectacle['id']) ?>" class="btn-details">Voir Détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p><?= $upcomingSpectacles['message'] ?></p>
            <?php endif; ?>
        </section>
    </div>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2024 Spectacles en Lumière. Tous droits réservés.</p>
    </footer>

    <script>
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const suggestionsList = document.getElementById('suggestions');

        let selectedId = null; // Variable pour stocker l'ID du spectacle sélectionné

        // Fonction de gestion de la recherche avec suggestions
        searchInput.addEventListener('input', async () => {
            const query = searchInput.value;

            if (query.length < 2) {
                suggestionsList.innerHTML = ''; // Si moins de 2 caractères, ne pas afficher
                return;
            }

            try {
                const response = await fetch(`get_suggestions.php?query=${encodeURIComponent(query)}`);
                const result = await response.json();

                if (result.success) {
                    displaySuggestions(result.data);
                } else {
                    suggestionsList.innerHTML = '<li>Aucune suggestion</li>';
                }
            } catch (error) {
                console.error('Error fetching suggestions:', error);
            }
        });

        // Afficher les suggestions sous forme de liste
        function displaySuggestions(suggestions) {
            suggestionsList.innerHTML = suggestions
                .map(suggestion => `<li onclick="selectSuggestion(${suggestion.id})">${suggestion.title}</li>`)
                .join('');
        }

        // Lorsque l'utilisateur sélectionne une suggestion
        function selectSuggestion(id) {
            selectedId = id; 
            searchInput.value = ''; 
            suggestionsList.innerHTML = ''; 
            window.location.href = `spectacle_details.php?id=${id}`;
        }

        // Lors de la soumission du formulaire
        searchForm.addEventListener("submit", (e) => {
            e.preventDefault();
            window.location.href = `spectacle_details.php?id=${id}`;
        });

    </script>

</body>

</html>