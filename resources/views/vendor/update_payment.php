<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'vendor') {
    die("Unauthorized access");
}

// Database connection
@include "../mydatabase/conn.php";
// Get data from POST
$order_id = intval($_POST['order_id']);
$payment_id = $_POST['payment_id'];
$vendor_id = $_SESSION['user_id'];

// Verify the order belongs to this vendor
$verify_query = "SELECT * FROM orders WHERE id = ? AND vendor_id = ?";
$stmt = $conn->prepare($verify_query);
$stmt->bind_param("ii", $order_id, $vendor_id);
$stmt->execute();
$verify_result = $stmt->get_result();
$order = $verify_result->fetch_assoc();

if ($verify_result->num_rows === 0) {
    die("Order not found or unauthorized access");
}
elseif ($order['status'] == "paid") {
    die("Already paid.");
}


$sql = "SELECT transaction_ref, amount FROM payments WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $transaction_ref = $row['transaction_ref'];
    $order_price = $row['amount'];
} 
else {
    echo "No transaction found for this order ID.";
}

$payment_status = ($transaction_ref == $payment_id) ? "paid" : "unpaid";

// Update payment status status
if ($payment_status == "paid") {
    $update_query = "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $payment_status, $order_id);
    $stmt->execute();

    $current_bal = $conn->query("SELECT total_balance FROM vendor_balance WHERE user_id = $vendor_id;")->fetch_assoc()['total_balance'];
    $new_bal = $current_bal + $order['total_amount'];
    $update_bal = $conn->prepare("UPDATE vendor_balance SET total_balance = ? WHERE user_id = ?");
    $update_bal->bind_param("ii", $new_bal, $vendor_id);
    $update_bal->execute();
}

// if ($stmt->execute()) {
//     echo "Success";
// } else {
//     echo "Error: " . $conn->error;
// }

header("location: orders.php");
$conn->close();
?>