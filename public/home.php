<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/SpectacleController.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';

use Controllers\SpectacleController;
use Controllers\UserController;

session_start();

// Initialize UserController
$userController = new UserController();

// Handle logout action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $logoutResult = $userController->logout();
    header('Location: home.php'); // Redirect to the homepage after logout
    exit();
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? ''; // Optional: use username in UI

// Initialize SpectacleController
$spectacleController = new SpectacleController();

// Handle search filters
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

// Get spectacles based on filters
$searchResults = $spectacleController->searchSpectacles($filters);
$upcomingSpectacles = $spectacleController->getUpcomingSpectacles();

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
    <!-- Header -->
    <header>
        <h1>Bienvenue sur notre site de spectacles</h1>
        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <a href="profile.php" class="btn">Profil</a>
                <a href="?action=logout" class="btn">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="signup.php" class="btn">Signup</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Search Form -->
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

        <!-- Upcoming Spectacles -->
        <section>
            <h2>Spectacles à venir</h2>
            <?php if ($upcomingSpectacles['success']): ?>
                <div class="spectacles-grid">
                    <?php foreach ($upcomingSpectacles['data'] as $spectacle): ?>
                        <div class="spectacle-card">
                            <img src="<?= htmlspecialchars($spectacle['image'] ?? '/placeholder.svg?height=200&width=300') ?>" alt="<?= htmlspecialchars($spectacle['title']) ?>" class="spectacle-image" >
                            <h3><?= htmlspecialchars($spectacle['title']) ?></h3>
                            <p><strong>Date :</strong> <?= htmlspecialchars($spectacle['date']) ?></p>
                            <p class="spectacle-synopsis"><strong>Description :</strong> <?= htmlspecialchars($spectacle['synopsis'] ?? 'Non spécifié') ?></p>
                            <a href="spectacle_details.php?id=<?= htmlspecialchars($spectacle['id']) ?>" class="btn-details">Voir Détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucun spectacle à venir.</p>
            <?php endif; ?>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Spectacles en Lumière. Tous droits réservés.</p>
    </footer>

    <script>
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const suggestionsList = document.getElementById('suggestions');

        let selectedId = null;

        // Gestion de la recherche avec suggestions
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
                .map(suggestion => `<li onclick="selectSuggestion(${suggestion.id})">${suggestion.title}</li>`)
                .join('');
        }

        searchForm.addEventListener("submit", (e) => {
            e.preventDefault();
            if (selectedId) {
                window.location.href = `spectacle_details.php?id=${selectedId}`;
            }
        });

        function selectSuggestion(id) {
            selectedId = id;
            searchInput.value = '';
            suggestionsList.innerHTML = '';
            window.location.href = `spectacle_details.php?id=${id}`;
        }
    </script>

</body>

</html>