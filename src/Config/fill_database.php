<?php

namespace Config;

require_once __DIR__ . '/../../vendor/autoload.php';

use Config\Database;
use Faker\Factory;

$faker = Factory::create('fr_FR');
$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Fill Roles
    $roles = [
        ['name' => 'Admin', 'description' => 'System administrator'],
        ['name' => 'Subscriber', 'description' => 'Regular subscriber'],
        ['name' => 'Moderator', 'description' => 'Content moderator']
    ];

    foreach ($roles as $role) {
        $stmt = $conn->prepare("INSERT IGNORE INTO roles (name, description) VALUES (?, ?)");
        $stmt->execute([$role['name'], $role['description']]);
    }
    echo "Roles added successfully.\n";

    // 2. Create Admin Account
    $adminPassword = password_hash('jesuisla', PASSWORD_BCRYPT);
    $conn->prepare("INSERT IGNORE INTO spectacles_subscriber (first_name, last_name, email, username, password, birthdate, role_id)
                    VALUES ('Admin', 'User', 'admin@example.com', 'admin', ?, '1990-01-01', 
                            (SELECT id FROM roles WHERE name = 'Admin'))")
         ->execute([$adminPassword]);

    echo "Admin account created successfully.\n";

    // 3. Fill Categories
    for ($i = 0; $i < 5; $i++) {
        $conn->prepare("INSERT INTO spectacles_category (name, help_text) VALUES (?, ?)")
             ->execute([$faker->word(), $faker->sentence()]);
    }
    echo "Categories added successfully.\n";

    // 4. Fill Theatres
    for ($i = 0; $i < 3; $i++) {
        $conn->prepare("INSERT INTO spectacles_theatre (name, presentation, address, borough, geolocation, phone, email)
                        VALUES (?, ?, ?, ?, ST_GeomFromText(?), ?, ?)")
             ->execute([$faker->company(), $faker->paragraph(), $faker->address(), $faker->numberBetween(1, 20),
                        "POINT({$faker->latitude()} {$faker->longitude()})", $faker->phoneNumber(), $faker->email()]);
    }
    echo "Theatres added successfully.\n";

    // 5. Fill Rooms
    for ($i = 0; $i < 5; $i++) {
        $conn->prepare("INSERT INTO spectacles_room (name, gauge, theatre_id) VALUES (?, ?, ?)")
             ->execute([$faker->word(), $faker->numberBetween(50, 200), $faker->numberBetween(1, 3)]);
    }
    echo "Rooms added successfully.\n";

    // 6. Fill Spectacles
    for ($i = 0; $i < 10; $i++) {
        $conn->prepare("INSERT INTO spectacles_spectacle (title, synopsis, duration, price, category_id, language)
                        VALUES (?, ?, ?, ?, ?, ?)")
             ->execute([$faker->sentence(3), $faker->text(200), $faker->time('H:i:s'),
                        $faker->randomFloat(2, 10, 100), $faker->numberBetween(1, 5), $faker->randomElement(['français', 'autre'])]);
    }
    echo "Spectacles added successfully.\n";

    // 7. Fill Performances
    for ($i = 0; $i < 15; $i++) {
        $conn->prepare("INSERT INTO spectacles_performance (spectacle_id, room_id, first_date, last_date)
                        VALUES (?, ?, ?, ?)")
             ->execute([$faker->numberBetween(1, 10), $faker->numberBetween(1, 5),
                        $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s'),
                        $faker->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d H:i:s')]);
    }
    echo "Performances added successfully.\n";

    // 8. Fill Artists
    for ($i = 0; $i < 10; $i++) {
        $conn->prepare("INSERT INTO spectacles_artist (first_name, last_name, biography) VALUES (?, ?, ?)")
             ->execute([$faker->firstName(), $faker->lastName(), $faker->paragraph()]);
    }
    echo "Artists added successfully.\n";

    // 9. Fill Activities
    for ($i = 0; $i < 15; $i++) {
        $conn->prepare("INSERT INTO spectacles_activity (role, artist_id, spectacle_id) VALUES (?, ?, ?)")
             ->execute([$faker->jobTitle(), $faker->numberBetween(1, 10), $faker->numberBetween(1, 10)]);
    }
    echo "Activities added successfully.\n";

    // 10. Fill Subscribers
    for ($i = 0; $i < 10; $i++) {
        $conn->prepare("INSERT INTO spectacles_subscriber (first_name, last_name, email, username, password, birthdate, role_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?)")
             ->execute([$faker->firstName(), $faker->lastName(), $faker->unique()->email(), $faker->unique()->userName(),
                        password_hash($faker->password(), PASSWORD_BCRYPT), $faker->date('Y-m-d', '-18 years'), 2]);
    }
    echo "Subscribers added successfully.\n";

    // 11. Fill Reviews
    for ($i = 0; $i < 15; $i++) {
        $conn->prepare("INSERT INTO reviews (spectacle_id, subscriber_id, comment, rating)
                        VALUES (?, ?, ?, ?)")
             ->execute([$faker->numberBetween(1, 10), $faker->numberBetween(1, 10),
                        $faker->paragraph(), $faker->numberBetween(1, 5)]);
    }
    echo "Reviews added successfully.\n";

    // 12. Fill Schedule
    for ($i = 0; $i < 20; $i++) {
        $conn->prepare("INSERT INTO spectacles_schedule (day, booked, paid, amount, comment, spectacle_id, subscriber_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?)")
             ->execute([
                 $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s'),
                 $faker->randomElement([0, 1]),
                 $faker->randomElement([0, 1]),
                 $faker->randomFloat(2, 10, 100),
                 $faker->optional(0.5, $faker->sentence())->text(),
                 $faker->numberBetween(1, 10),
                 $faker->optional(0.5, null)->numberBetween(1, 10)
             ]);
    }
    echo "Schedule added successfully.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
