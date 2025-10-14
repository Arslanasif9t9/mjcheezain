<?php

session_start();
$user_id = $_SESSION['user_id']; // Ensure user is logged in

require '../mydatabase/conn.php'; // Your DB connection

$sql = "SELECT activity_type, title, value, created_at 
        FROM customer_recent_activity 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 3";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>
