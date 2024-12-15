<?php

namespace Config;

require_once __DIR__ . '/../../vendor/autoload.php';

use Config\Database;
use Faker\Factory;

// Initialize Faker
$faker = Factory::create('fr_FR');

// Database Connection
$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Fill Roles (Avoiding duplicates)
    $roles = [
        ['name' => 'Admin', 'description' => 'System administrator'],
        ['name' => 'Subscriber', 'description' => 'Regular subscriber'],
        ['name' => 'Moderator', 'description' => 'Content moderator']
    ];

    foreach ($roles as $role) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM roles WHERE name = ?");
        $stmt->execute([$role['name']]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            $conn->prepare("INSERT INTO roles (name, description) VALUES (?, ?)")
                ->execute([$role['name'], $role['description']]);
        }
    }
    echo "Roles added successfully.\n";

    // 2. Create Admin Account (Avoiding duplicates)
    $adminPassword = password_hash('jesuisla', PASSWORD_BCRYPT); // Securely hash the password
    $stmt = $conn->prepare("SELECT COUNT(*) FROM spectacles_subscriber WHERE username = ?");
    $stmt->execute(['admin']);
    $adminExists = $stmt->fetchColumn();

    if (!$adminExists) {
        // Fetch the role_id for Admin
        $stmt = $conn->prepare("SELECT id FROM roles WHERE name = ?");
        $stmt->execute(['Admin']);
        $adminRoleId = $stmt->fetchColumn();

        // Insert the Admin user
        $conn->prepare("INSERT INTO spectacles_subscriber (first_name, last_name, email, username, password, birthdate, role_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                'Admin',
                'User',
                'admin@example.com',
                'admin',
                $adminPassword,
                '1990-01-01',
                $adminRoleId
            ]);
        echo "Admin account created successfully.\n";
    } else {
        echo "Admin account already exists.\n";
    }

    // 3. Fill Categories
    for ($i = 0; $i < 5; $i++) {
        $name = $faker->word();
        $help_text = $faker->sentence();
        $conn->prepare("INSERT INTO spectacles_category (name, help_text) VALUES (?, ?)")
            ->execute([$name, $help_text]);
    }
    echo "Categories added successfully.\n";

    // 4. Fill Theatres
    for ($i = 0; $i < 3; $i++) {
        $name = $faker->company();
        $presentation = $faker->paragraph();
        $address = $faker->address();
        $borough = $faker->numberBetween(1, 20);
        $latitude = $faker->latitude();
        $longitude = $faker->longitude();
        $phone = $faker->phoneNumber();
        $email = $faker->email();

        $sql = "INSERT INTO spectacles_theatre (name, presentation, address, borough, geolocation, phone, email)
                VALUES (?, ?, ?, ?, ST_GeomFromText(?), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $presentation, $address, $borough, "POINT($latitude $longitude)", $phone, $email]);
    }
    echo "Theatres added successfully.\n";

    // 5. Fill Rooms
    for ($i = 0; $i < 5; $i++) {
        $name = $faker->word();
        $gauge = $faker->numberBetween(50, 200);
        $theatre_id = $faker->numberBetween(1, 3);
        $conn->prepare("INSERT INTO spectacles_room (name, gauge, theatre_id) VALUES (?, ?, ?)")
            ->execute([$name, $gauge, $theatre_id]);
    }
    echo "Rooms added successfully.\n";

    // 6. Fill Spectacles
    for ($i = 0; $i < 10; $i++) {
        $title = $faker->sentence(3);
        $synopsis = $faker->text(200);
        $duration = $faker->numberBetween(60, 180); // Duration in minutes
        $price = $faker->randomFloat(2, 10, 100);
        $category_id = $faker->numberBetween(1, 5);
        $language = $faker->randomElement(['français', 'autre']);
        $conn->prepare("INSERT INTO spectacles_spectacle (title, synopsis, duration, price, category_id, language) 
                    VALUES (?, ?, ?, ?, ?, ?)")
            ->execute([$title, $synopsis, $duration, $price, $category_id, $language]);
    }
    echo "Spectacles added successfully.\n";

    // 7. Fill Performances
    for ($i = 0; $i < 15; $i++) {
        $spectacle_id = $faker->numberBetween(1, 10);
        $room_id = $faker->numberBetween(1, 5);
        $first_date = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s');
        $last_date = $faker->dateTimeBetween($first_date, '+1 month')->format('Y-m-d H:i:s');
        $conn->prepare("INSERT INTO spectacles_performance (spectacle_id, room_id, first_date, last_date) 
                        VALUES (?, ?, ?, ?)")
            ->execute([$spectacle_id, $room_id, $first_date, $last_date]);
    }
    echo "Performances added successfully.\n";

    // 8. Fill Artists
    for ($i = 0; $i < 10; $i++) {
        $first_name = $faker->firstName();
        $last_name = $faker->lastName();
        $biography = $faker->paragraph();
        $conn->prepare("INSERT INTO spectacles_artist (first_name, last_name, biography) VALUES (?, ?, ?)")
            ->execute([$first_name, $last_name, $biography]);
    }
    echo "Artists added successfully.\n";

    // 9. Fill Activities
    for ($i = 0; $i < 15; $i++) {
        $role = $faker->jobTitle();
        $artist_id = $faker->numberBetween(1, 10);
        $spectacle_id = $faker->numberBetween(1, 10);
        $conn->prepare("INSERT INTO spectacles_activity (role, artist_id, spectacle_id) VALUES (?, ?, ?)")
            ->execute([$role, $artist_id, $spectacle_id]);
    }
    echo "Activities added successfully.\n";

    // 10. Fill Subscribers
    for ($i = 0; $i < 10; $i++) {
        $first_name = $faker->firstName();
        $last_name = $faker->lastName();
        $email = $faker->unique()->email();
        $username = $faker->unique()->userName();
        $password = password_hash($faker->password(), PASSWORD_BCRYPT);
        $birthdate = $faker->date('Y-m-d', '-18 years');
        $role_id = 2; // Assign all other subscribers as regular users

        $conn->prepare("INSERT INTO spectacles_subscriber (first_name, last_name, email, username, password, birthdate, role_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([$first_name, $last_name, $email, $username, $password, $birthdate, $role_id]);
    }
    echo "Subscribers added successfully.\n";

    // 11. Fill Reviews
    for ($i = 0; $i < 15; $i++) {
        $spectacle_id = $faker->numberBetween(1, 10);
        $subscriber_id = $faker->numberBetween(1, 10);
        $comment = $faker->paragraph();
        $rating = $faker->numberBetween(1, 5);
        $conn->prepare("INSERT INTO reviews (spectacle_id, subscriber_id, comment, rating) 
                        VALUES (?, ?, ?, ?)")
            ->execute([$spectacle_id, $subscriber_id, $comment, $rating]);
    }
    echo "Reviews added successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
