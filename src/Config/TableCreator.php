<?php

namespace Config;

use PDOException;

class TableCreator
{
    public static function createTables()
    {
        $db = new Database();
        $conn = $db->getConnection();

        try {
            $sql = "
            -- Create roles table
            CREATE TABLE IF NOT EXISTS roles (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL UNIQUE,
                description TEXT
            );

            -- Create spectacles_category table
            CREATE TABLE IF NOT EXISTS spectacles_category (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                help_text TEXT
            );

            -- Create spectacles_theatre table
            CREATE TABLE IF NOT EXISTS spectacles_theatre (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                presentation TEXT,
                address TEXT,
                borough INT(11),
                geolocation POINT NOT NULL,
                phone VARCHAR(20),
                email VARCHAR(128)
            );

            -- Create spectacles_room table
            CREATE TABLE IF NOT EXISTS spectacles_room (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(200) NOT NULL,
                gauge INT(11),
                theatre_id INT(11),
                FOREIGN KEY (theatre_id) REFERENCES spectacles_theatre(id)
            );

            -- Create spectacles_spectacle table
            CREATE TABLE IF NOT EXISTS spectacles_spectacle (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                synopsis TEXT,
                duration TIME,
                price FLOAT,
                category_id INT(11),
                language ENUM('français', 'autre'),
                FOREIGN KEY (category_id) REFERENCES spectacles_category(id)
            );

            -- Create spectacles_performance table
            CREATE TABLE IF NOT EXISTS spectacles_performance (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                spectacle_id INT(11),
                room_id INT(11),
                first_date DATETIME,
                last_date DATETIME,
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id),
                FOREIGN KEY (room_id) REFERENCES spectacles_room(id)
            );

            -- Create spectacles_artist table
            CREATE TABLE IF NOT EXISTS spectacles_artist (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(100),
                last_name VARCHAR(100),
                biography TEXT
            );

            -- Create spectacles_activity table
            CREATE TABLE IF NOT EXISTS spectacles_activity (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                role VARCHAR(60),
                artist_id INT(11),
                spectacle_id INT(11),
                FOREIGN KEY (artist_id) REFERENCES spectacles_artist(id),
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id)
            );

            -- Create spectacles_subscriber table
            CREATE TABLE IF NOT EXISTS spectacles_subscriber (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(64) NOT NULL,
                last_name VARCHAR(128) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                username VARCHAR(48) NOT NULL UNIQUE,
                password VARCHAR(128) NOT NULL,
                birthdate DATE,
                role_id INT(11) DEFAULT 2, -- Default to Subscriber role
                FOREIGN KEY (role_id) REFERENCES roles(id)
            );

            -- Create spectacles_schedule table
            CREATE TABLE IF NOT EXISTS spectacles_schedule (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                day DATETIME,
                booked TINYINT(3) UNSIGNED,
                paid TINYINT(1),
                amount FLOAT,
                comment TEXT, -- New comment field added
                spectacle_id INT(11),
                subscriber_id INT(11),
                reactions JSON,
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id),
                FOREIGN KEY (subscriber_id) REFERENCES spectacles_subscriber(id)
            );

            -- Create reviews table
            CREATE TABLE IF NOT EXISTS reviews (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                spectacle_id INT(11) NOT NULL,
                subscriber_id INT(11) NOT NULL,
                comment TEXT NOT NULL,
                rating SMALLINT(5) UNSIGNED CHECK (rating BETWEEN 1 AND 5),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id) ON DELETE CASCADE,
                FOREIGN KEY (subscriber_id) REFERENCES spectacles_subscriber(id) ON DELETE CASCADE
            );
            ";

            $conn->exec($sql);
            echo "Tables created successfully.\n";

        } catch (PDOException $e) {
            echo "Error creating tables: " . $e->getMessage() . "\n";
        } finally {
            $conn = null;
        }
    }
}
