<?php
session_start();
header('Content-Type: application/json');

include '../mydatabase/conn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Sorting
$sort = $_GET['sort'] ?? 'recent';
$order_by = match($sort) {
    'low_high' => 'vp.selling_price ASC',
    'high_low' => 'vp.selling_price DESC',
    'name_asc' => 'vp.name ASC',
    'name_desc' => 'vp.name DESC',
    default => 'f.created_at DESC',
};

// Filter
$filter = $_GET['filter'] ?? 'all';
$stock_filter = '';
if ($filter === 'in_stock') $stock_filter = 'AND vp.quantity > 0';
elseif ($filter === 'out_of_stock') $stock_filter = 'AND vp.quantity = 0';
elseif ($filter === 'limited') $stock_filter = 'AND vp.quantity BETWEEN 1 AND 5';

$sql = "
SELECT vp.*, f.*, f.id as fav_id, i.image_path
FROM favorites f
JOIN vendor_products vp ON f.product_id = vp.id
LEFT JOIN vendor_product_images i ON vp.id = i.product_id AND i.is_primary = 1
WHERE f.user_id = ? $stock_filter
ORDER BY $order_by
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlist = [];
while ($row = $result->fetch_assoc()) {
    $wishlist[] = $row;
}

echo json_encode($wishlist);
?>
