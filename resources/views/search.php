<?php
header('Content-Type: application/json');

@include "./mydatabase/conn.php";

// Get search parameters from request
$searchTerm = isset($_POST['model']) ? $conn->real_escape_string($_POST['model']) : '';
$city = isset($_POST['city']) ? $conn->real_escape_string($_POST['city']) : '';
$priceRange = isset($_POST['priceRange']) ? $_POST['priceRange'] : '';
$brand = isset($_POST['brand']) ? $conn->real_escape_string($_POST['brand']) : '';
$condition = isset($_POST['condition']) ? $conn->real_escape_string($_POST['condition']) : '';

// Build the SQL query with filters
$sql = "SELECT 
        vp.id, 
        vp.name, 
        vp.brand, 
        vp.model, 
        vp.condition, 
        vp.selling_price, 
        vp.location,
        vp.description,
        vbi.store_name,
        vbi.rating,
        (SELECT image_path FROM vendor_product_images WHERE product_id = vp.id LIMIT 1) as image_path
        FROM vendor_products vp
        JOIN vendor_basic_info vbi ON vp.user_id = vbi.user_id
        WHERE vp.position = 'approved'";

// Add filters if provided
if (!empty($searchTerm)) {
    $sql .= " AND (vp.name LIKE '%$searchTerm%' OR vp.model LIKE '%$searchTerm%' OR vp.brand LIKE '%$searchTerm%')";
}

if (!empty($city)) {
    $sql .= " AND vp.location LIKE '%$city%'";
}

if (!empty($brand)) {
    $sql .= " AND vp.brand = '$brand'";
}

if (!empty($condition)) {
    $sql .= " AND vp.condition = '$condition'";
}

// Handle price range
if (!empty($priceRange)) {
    switch ($priceRange) {
        case 'Below 10 Lakh':
            $sql .= " AND vp.selling_price < 1000000";
            break;
        case '10 - 20 Lakh':
            $sql .= " AND vp.selling_price BETWEEN 1000000 AND 2000000";
            break;
        case 'Above 20 Lakh':
            $sql .= " AND vp.selling_price > 2000000";
            break;
    }
}

$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

echo json_encode([
    'success' => true,
    'count' => count($products),
    'products' => $products
]);
?>