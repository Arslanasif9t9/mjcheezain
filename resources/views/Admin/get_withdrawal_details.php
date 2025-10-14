<?php
require_once '../mydatabase/conn.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Missing withdrawal ID']);
    exit;
}

$id = (int)$_GET['id'];

$query = "SELECT wr.*, 
          u.username, u.email, u.phone,
          vbi.full_name as vendor_name, vbi.profile_picture,
          vb.total_balance as vendor_balance
          FROM withdrawal_requests wr
          JOIN users u ON wr.user_id = u.user_id
          JOIN vendor_basic_info vbi ON wr.user_id = vbi.user_id
          LEFT JOIN vendor_balance vb ON wr.user_id = vb.user_id
          WHERE wr.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Withdrawal request not found']);
    exit;
}

$withdrawal = $result->fetch_assoc();
echo json_encode($withdrawal);
?>