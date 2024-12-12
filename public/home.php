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
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
</head>

<body>
    <!-- En-tête -->
    <header>
        <h1>Bienvenue sur notre site de spectacles</h1>
        <p>Explorez nos spectacles, découvrez les nouveautés et réservez dès maintenant !</p>
    </header>

    <!-- Contenu principal -->
    <div class="container">
        <!-- Moteur de recherche -->
        <section>
            <h2>Rechercher un spectacle</h2>
            <form method="GET" action="">
                <div class="search-container">
                    <input
                        type="text"
                        id="search-input"
                        placeholder="Rechercher un spectacle..."
                        autocomplete="on" />
                    <ul id="suggestions" class="suggestions-list"></ul>
                </div>
                <button type="submit" class="btn">Rechercher</button>
            </form>
        </section>

        <!-- Résultats de recherche -->
        <section>
            <h2>Résultats de la recherche</h2>
            <?php if ($searchResults['success']): ?>
                <div class="spectacles-grid">
                    <?php foreach ($searchResults['data'] as $spectacle): ?>
                        <div class="spectacle-card">
                            <h2><?= htmlspecialchars($spectacle['title']) ?></h2>
                            <p><strong>Date :</strong> <?= htmlspecialchars($spectacle['date']) ?></p>
                            <p><strong>Description :</strong> <?= htmlspecialchars($spectacle[' synopsis'] ?? 'Non spécifié') ?></p>
                            <a href="spectacle_details.php?id=<?= htmlspecialchars($spectacle['id']) ?>" class="btn-details">Voir Détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p><?= $searchResults['message'] ?></p>
            <?php endif; ?>
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
                            <p><strong>Description :</strong> <?= htmlspecialchars($spectacle[' synopsis'] ?? 'Non spécifié') ?></p>
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
</body>

<script>
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions');

    searchInput.addEventListener('input', async () => {
        const query = searchInput.value;

        if (query.length < 2) {
            suggestionsList.innerHTML = '';
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

    function displaySuggestions(suggestions) {
        suggestionsList.innerHTML = suggestions
            .map(suggestion => `<li onclick="selectSuggestion('${suggestion.title}')">${suggestion.title}</li>`)
            .join('');
    }

    function selectSuggestion(title) {
        searchInput.value = title;
        suggestionsList.innerHTML = '';
    }
</script>

</html>
