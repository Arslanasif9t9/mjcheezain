<?php
session_start();
require_once '../mydatabase/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$amount = floatval($_POST['amount'] ?? 0);
$method = $_POST['method'] ?? '';
$account_number = $_POST['account_number'] ?? '';
$account_holder = $_POST['account_holder'] ?? '';
$bank_name = $_POST['bank_name'] ?? '';

// Validate minimum amount
if ($amount < 500) {
    echo json_encode(['success' => false, 'message' => 'Minimum withdrawal amount is 500 PKR']);
    exit;
}

// Check vendor balance
$balance_query = $conn->prepare("SELECT total_balance FROM vendor_balance WHERE user_id = ?");
$balance_query->bind_param("i", $user_id);
$balance_query->execute();
$balance_result = $balance_query->get_result();

if ($balance_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No balance available']);
    exit;
}

$balance = $balance_result->fetch_assoc()['total_balance'];

if ($amount > $balance) {
    echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
    exit;
}

// Validate method
$allowed_methods = ['jazzcash', 'easypaisa', 'bank_transfer'];
if (!in_array($method, $allowed_methods)) {
    echo json_encode(['success' => false, 'message' => 'Invalid withdrawal method']);
    exit;
}

// Validate account details
if (empty($account_number) || empty($account_holder)) {
    echo json_encode(['success' => false, 'message' => 'Account details are required']);
    exit;
}

// For bank transfer, bank name is required
if ($method === 'bank_transfer' && empty($bank_name)) {
    echo json_encode(['success' => false, 'message' => 'Bank name is required for bank transfer']);
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert withdrawal request
    $stmt = $conn->prepare("INSERT INTO withdrawal_requests 
        (user_id, amount, withdrawal_method, bank_name, account_number, account_holder_name) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idssss", $user_id, $amount, $method, $bank_name, $account_number, $account_holder);
    $stmt->execute();
    
    // Deduct from vendor balance
    $update_balance = $conn->prepare("UPDATE vendor_balance SET total_balance = total_balance - ? WHERE user_id = ?");
    $update_balance->bind_param("di", $amount, $user_id);
    $update_balance->execute();
    
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Withdrawal request submitted successfully']);
} 
catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error processing withdrawal: ' . $e->getMessage()]);
}
?>