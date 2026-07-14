<?php
// Database connection parameters (from .env — never hardcode credentials)
$servername = env('DB_HOST', 'localhost');
$username = env('DB_USERNAME', 'root');
$password = env('DB_PASSWORD', '');
$dbname = env('DB_DATABASE', 'u425346958_db');

// Create connetion
$conn = new mysqli($servername, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
