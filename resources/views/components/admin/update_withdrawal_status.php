<?php
require_once '../mydatabase/conn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$requestId = $data['requestId'] ?? null;
$newStatus = $data['newStatus'] ?? null;

if (!$requestId || !$newStatus) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

// Validate status
$allowedStatuses = ['pending', 'processing', 'completed', 'rejected'];
if (!in_array($newStatus, $allowedStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

// Update the request
$stmt = $conn->prepare("UPDATE withdrawal_requests SET status = ?, processed_at = NOW() WHERE id = ?");
$stmt->bind_param("si", $newStatus, $requestId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>