<?php
session_start();
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 1; // use real session in production

@include "../mydatabase/conn.php";

$status = $_GET['status'] ?? 'all';

$sql = "SELECT 
            orders.id AS order_id,
            orders.order_date,
            orders.quantity,
            orders.total_amount,
            orders.fulfillment
        FROM orders
        WHERE orders.user_id = ?";

if ($status !== 'all') {
    $sql .= " AND orders.fulfillment = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $status);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
