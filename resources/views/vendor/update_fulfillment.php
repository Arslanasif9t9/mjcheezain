<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'vendor') {
    die("Unauthorized access");
}

// Database connection
@include "../mydatabase/conn.php";
// Get data from POST
$order_id = intval($_POST['order_id']);
$fulfillment = $_POST['fulfillment'];
$vendor_id = $_SESSION['user_id'];

// Verify the order belongs to this vendor
$verify_query = "SELECT id FROM orders WHERE id = ? AND vendor_id = ?";
$stmt = $conn->prepare($verify_query);
$stmt->bind_param("ii", $order_id, $vendor_id);
$stmt->execute();
$verify_result = $stmt->get_result();

if ($verify_result->num_rows === 0) {
    die("Order not found or unauthorized access");
}

// Update fulfillment status
$update_query = "UPDATE orders SET fulfillment = ?, updated_at = NOW() WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("si", $fulfillment, $order_id);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>