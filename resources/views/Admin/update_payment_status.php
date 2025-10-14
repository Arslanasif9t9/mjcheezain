<?php
header('Content-Type: application/json');
@include "../mydatabase/conn.php";

$response = ['success' => false, 'message' => ''];

try {
    // Get the input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['payment_id']) || !isset($input['status'])) {
        throw new Exception('Invalid input data');
    }
    
    $paymentId = (int)$input['payment_id'];
    $status = $input['status'];
    
    // Validate status
    $allowedStatuses = ['pending', 'verified', 'rejected'];
    if (!in_array($status, $allowedStatuses)) {
        throw new Exception('Invalid payment status');
    }

    $stmt = $conn->prepare("SELECT order_id FROM payments WHERE id = ?");
    $stmt->bind_param("i", $paymentId);
    $stmt->execute();
    $orderId = $stmt->get_result()->fetch_assoc()['order_id'];
    
    // Update payment status
    $stmt = $conn->prepare("UPDATE payments SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $paymentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update payment status');
    }

    $stmt = $conn->prepare("UPDATE orders SET vendor_visible = True WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update order visibility status');
    }
    
    // If payment is verified, you might want to trigger other actions here
    // For example, notify vendor, update order status, etc.
    
    $response['success'] = true;
    $response['message'] = 'Payment status updated successfully';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}

echo json_encode($response);
?>