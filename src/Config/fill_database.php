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
    // 1. Fill Categories
    for ($i = 0; $i < 5; $i++) {
        $name = $faker->word();
        $help_text = $faker->sentence();
        $conn->prepare("INSERT INTO spectacles_category (name, help_text) VALUES (?, ?)")
             ->execute([$name, $help_text]);
    }
    echo "Categories added successfully.\n";

    // 2. Fill Theatres
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

    // 3. Fill Rooms
    for ($i = 0; $i < 5; $i++) {
        $name = $faker->word();
        $gauge = $faker->numberBetween(50, 200);
        $theatre_id = $faker->numberBetween(1, 3);
        $conn->prepare("INSERT INTO spectacles_room (name, gauge, theatre_id) VALUES (?, ?, ?)")
             ->execute([$name, $gauge, $theatre_id]);
    }
    echo "Rooms added successfully.\n";

    // 4. Fill Spectacles
    for ($i = 0; $i < 10; $i++) {
        $title = $faker->sentence(3);
        $synopsis = $faker->text(200);
        $duration = $faker->time('H:i:s');
        $price = $faker->randomFloat(2, 10, 100);
        $category_id = $faker->numberBetween(1, 5);
        $language = $faker->randomElement(['français', 'autre']);
        $conn->prepare("INSERT INTO spectacles_spectacle (title, synopsis, duration, price, category_id, language) 
                        VALUES (?, ?, ?, ?, ?, ?)")
             ->execute([$title, $synopsis, $duration, $price, $category_id, $language]);
    }
    echo "Spectacles added successfully.\n";

    // 5. Fill Performances
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

    // 6. Fill Artists
    for ($i = 0; $i < 10; $i++) {
        $first_name = $faker->firstName();
        $last_name = $faker->lastName();
        $biography = $faker->paragraph();
        $conn->prepare("INSERT INTO spectacles_artist (first_name, last_name, biography) VALUES (?, ?, ?)")
             ->execute([$first_name, $last_name, $biography]);
    }
    echo "Artists added successfully.\n";

    // 7. Fill Activities
    for ($i = 0; $i < 15; $i++) {
        $role = $faker->jobTitle();
        $artist_id = $faker->numberBetween(1, 10);
        $spectacle_id = $faker->numberBetween(1, 10);
        $conn->prepare("INSERT INTO spectacles_activity (role, artist_id, spectacle_id) VALUES (?, ?, ?)")
             ->execute([$role, $artist_id, $spectacle_id]);
    }
    echo "Activities added successfully.\n";

    // 8. Fill Subscribers
    for ($i = 0; $i < 10; $i++) {
        $first_name = $faker->firstName();
        $last_name = $faker->lastName();
        $email = $faker->unique()->email();
        $username = $faker->unique()->userName();
        $password = password_hash($faker->password(), PASSWORD_BCRYPT);
        $birthdate = $faker->date('Y-m-d', '-18 years');
        $conn->prepare("INSERT INTO spectacles_subscriber (first_name, last_name, email, username, password, birthdate) 
                        VALUES (?, ?, ?, ?, ?, ?)")
             ->execute([$first_name, $last_name, $email, $username, $password, $birthdate]);
    }
    echo "Subscribers added successfully.\n";

    // 9. Fill Schedules
    for ($i = 0; $i < 20; $i++) {
        $day = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s');
        $booked = $faker->numberBetween(0, 1);
        $paid = $faker->numberBetween(0, 1);
        $amount = $faker->randomFloat(2, 10, 100);
        $spectacle_id = $faker->numberBetween(1, 10);
        $subscriber_id = $faker->numberBetween(1, 10);
        $reactions = json_encode($faker->randomElements(['like', 'dislike', 'surprised', 'dubious'], $faker->numberBetween(1, 4)));
        $conn->prepare("INSERT INTO spectacles_schedule (day, booked, paid, amount, spectacle_id, subscriber_id, reactions) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)")
             ->execute([$day, $booked, $paid, $amount, $spectacle_id, $subscriber_id, $reactions]);
    }
    echo "Schedules added successfully.\n";

    // 10. Fill Reviews
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
