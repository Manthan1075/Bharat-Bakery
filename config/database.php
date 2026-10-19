<?php
// Database settings - change these if your XAMPP MySQL uses a password.
const DB_HOST = 'localhost';
const DB_NAME = 'bharat_bakery';
const DB_USER = 'root';
const DB_PASS = '';   // XAMPP default is empty

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            exit('<h2 style="font-family:sans-serif">Database connection failed.</h2><p style="font-family:sans-serif">Start MySQL in XAMPP and import <code>database.sql</code> in phpMyAdmin.</p>');
        }
    }
    return $pdo;
}
