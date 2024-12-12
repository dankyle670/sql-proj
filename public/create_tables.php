<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\TableCreator;

if (class_exists(TableCreator::class)) {
    echo "TableCreator class loaded successfully!<br>";
    TableCreator::createTables();
} else {
    echo "TableCreator class not found!";
}