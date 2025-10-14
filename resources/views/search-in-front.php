<?php
header('Content-Type: application/json');

@include './mydatabase/conn.php';

// Get parameters
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'All Categories';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 50; // 5 products per row × 2 rows

// Build query
$sql = "SELECT vp.*, vpi.image_path 
        FROM vendor_products vp
        LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
        WHERE vp.position = 'approved'";

if (!empty($searchTerm)) {
    $sql .= " AND (vp.name LIKE ? OR vp.description LIKE ? OR vp.brand LIKE ?)";
}

if ($category !== 'All Categories') {
    $sql .= " AND vp.category = ?";
}

$sql .= " ORDER BY vp.updated_at DESC LIMIT ?, ?";

// Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Prepare failed: ' . $conn->error]));
}

// Bind parameters
$offset = ($page - 1) * $perPage;
if (!empty($searchTerm)) {
    $searchParam = "%$searchTerm%";
    if ($category !== 'All Categories') {
        $stmt->bind_param("ssssii", $searchParam, $searchParam, $searchParam, $category, $offset, $perPage);
    } else {
        $stmt->bind_param("sssii", $searchParam, $searchParam, $searchParam, $offset, $perPage);
    }
} else {
    if ($category !== 'All Categories') {
        $stmt->bind_param("sii", $category, $offset, $perPage);
    } else {
        $stmt->bind_param("ii", $offset, $perPage);
    }
}

// Execute query
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'image' => !empty($row['image_path']) ? $row['image_path'] : 'img/default_img.png',
        'price' => $row['selling_price'],
        'mrp' => $row['mrp'],
        'updated_at' => $row['updated_at']
    ];
}

echo json_encode([
    'products' => $products,
    'page' => $page,
    'hasMore' => count($products) === $perPage
]);

$conn->close();
?>