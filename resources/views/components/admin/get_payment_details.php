<?php
header('Content-Type: application/json');
@include "../mydatabase/conn.php";

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid payment ID');
    }

    $paymentId = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT 
        p.*, 
        o.id AS order_id, 
        o.total_amount, 
        o.payment_method,
        o.order_date,
        vp.name AS product_name,
        vp.selling_price,
        u.username AS customer_username,
        cp.first_name AS customer_name,
        v.username AS vendor_username,
        vbi.store_name AS vendor_store
    FROM payments p
    JOIN orders o ON p.order_id = o.id
    JOIN vendor_products vp ON o.product_id = vp.id
    JOIN users u ON o.user_id = u.user_id
    JOIN customer_profile cp ON o.user_id = cp.user_id
    JOIN users v ON o.vendor_id = v.user_id
    JOIN vendor_basic_info vbi ON o.vendor_id = vbi.user_id
    WHERE p.id = ?");
    
    $stmt->bind_param("i", $paymentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Payment not found');
    }

    $payment = $result->fetch_assoc();
    
    // Format dates for display
    $payment['transaction_date'] = date('Y-m-d H:i:s', strtotime($payment['transaction_date']));
    $payment['order_date'] = date('Y-m-d H:i:s', strtotime($payment['order_date']));
    
    $response['success'] = true;
    $response['payment'] = $payment;
    
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