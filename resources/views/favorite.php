<?php
session_start();
include './mydatabase/conn.php'; // Your DB connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo 'not_logged_in';
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    echo 'no_product';
    exit;
}

// Check if already favorited
$sql = "SELECT * FROM favorites WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove favorite
    $del = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $del->bind_param("ii", $user_id, $product_id);
    $del->execute();
    echo 'removed';
} else {
    // Add favorite
    $ins = $conn->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $product_id);
    if ($ins->execute()) {
        echo 'added';
    } else {
        echo 'error';
    }
}
?>
