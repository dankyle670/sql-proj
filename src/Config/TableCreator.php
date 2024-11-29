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
            CREATE TABLE IF NOT EXISTS spectacles_category (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                help_text TEXT
            );

            CREATE TABLE IF NOT EXISTS spectacles_theatre (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                presentation TEXT,
                address TEXT,
                borough INT(11),
                geolocation POINT,
                phone VARCHAR(20),
                email VARCHAR(128)
            );

            CREATE TABLE IF NOT EXISTS spectacles_room (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(200) NOT NULL,
                gauge INT(11),
                theatre_id INT(11),
                FOREIGN KEY (theatre_id) REFERENCES spectacles_theatre(id)
            );

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

            CREATE TABLE IF NOT EXISTS spectacles_performance (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                spectacle_id INT(11),
                room_id INT(11),
                first_date DATETIME,
                last_date DATETIME,
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id),
                FOREIGN KEY (room_id) REFERENCES spectacles_room(id)
            );

            CREATE TABLE IF NOT EXISTS spectacles_artist (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(100),
                last_name VARCHAR(100),
                biography TEXT
            );

            CREATE TABLE IF NOT EXISTS spectacles_activity (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                role VARCHAR(60),
                artist_id INT(11),
                spectacle_id INT(11),
                FOREIGN KEY (artist_id) REFERENCES spectacles_artist(id),
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id)
            );

            CREATE TABLE IF NOT EXISTS spectacles_subscriber (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(64) NOT NULL,
                last_name VARCHAR(128) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                username VARCHAR(48) NOT NULL UNIQUE,
                password VARCHAR(128) NOT NULL,
                birthdate DATE
            );

            CREATE TABLE IF NOT EXISTS spectacles_schedule (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                day DATETIME,
                booked TINYINT(3) UNSIGNED,
                paid TINYINT(1),
                amount FLOAT,
                comment TEXT,
                notation SMALLINT(5) UNSIGNED,
                spectacle_id INT(11),
                subscriber_id INT(11),
                reactions JSON,
                FOREIGN KEY (spectacle_id) REFERENCES spectacles_spectacle(id),
                FOREIGN KEY (subscriber_id) REFERENCES spectacles_subscriber(id)
            );
            ";

            $conn->exec($sql);
            echo "Tables created successfully.";

        } catch (PDOException $e) {
            echo "Error creating tables: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }
}
