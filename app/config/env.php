<?php
// config/env.php

use Dotenv\Dotenv;

// On vérifie si le fichier .env existe (cas local uniquement)
$envPath = dirname(__DIR__, 2) . '/.env';

if (file_exists($envPath)) {
    require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->load();
}

// Variables base de données
define('DB_DRIVER', $_ENV['DB_DRIVER'] ?? 'mysql');
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? '');
define('DB_PORT', $_ENV['DB_PORT'] ?? 5433);
define('DB_USER', $_ENV['DB_USER'] ?? '');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
define('DB_PATH', $_ENV['DB_PATH'] ?? '');
define('DSN', $_ENV['DSN'] ?? DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME);

// Configuration Twilio
define('TWILIO_SID', $_ENV['TWILIO_SID'] ?? '');
define('TWILIO_TOKEN', $_ENV['TWILIO_TOKEN'] ?? '');
define('TWILIO_PHONE_NUMBER', $_ENV['TWILIO_PHONE_NUMBER'] ?? '');
