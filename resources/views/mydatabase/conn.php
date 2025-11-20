<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u425346958_db";

// Create connetion
$conn = new mysqli($servername, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
