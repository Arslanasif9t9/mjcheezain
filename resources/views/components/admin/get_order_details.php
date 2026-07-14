<?php
// Database connection (from .env — never hardcode credentials)
$mysqli = new mysqli(
    function_exists('env') ? env('DB_HOST', 'localhost') : 'localhost',
    function_exists('env') ? env('DB_USERNAME', 'root') : 'root',
    function_exists('env') ? env('DB_PASSWORD', '') : '',
    function_exists('env') ? env('DB_DATABASE', 'cheezaindb') : 'cheezaindb'
);
if ($mysqli->connect_error) die("DB error");

// Get order ID
$order_id = intval($_GET['order_id']);

// Main order data
$order = $mysqli->query("SELECT o.*, cp.full_name, cp.phone, cp.email, ca.address_line1, ca.city, ca.country 
                         FROM orders o 
                         LEFT JOIN customer_profile cp ON o.user_id = cp.user_id
                         LEFT JOIN customer_addresses ca ON o.shipping_address_id = ca.id
                         WHERE o.id = $order_id")->fetch_assoc();

// Product data
$product = $mysqli->query("SELECT vp.name, vbi.store_name AS vendor, vp.selling_price AS price, 
                           vp.quantity, vp.selling_price * vp.quantity AS total, 
                           (SELECT image_path FROM vendor_product_images WHERE product_id = vp.id LIMIT 1) AS image 
                           FROM vendor_products vp
                           LEFT JOIN vendor_basic_info vbi ON vp.user_id = vbi.user_id
                           WHERE vp.id = " . $order['product_id'])->fetch_assoc();

echo json_encode([
    "order" => [
        "id" => $order['id'],
        "order_date" => $order['order_date'],
        "subtotal" => $order['subtotal'],
        "shipping" => $order['delivery_charges'],
        "discount" => 0,
        "total" => $order['total_amount']
    ],
    "customer" => [
        "full_name" => $order['full_name'],
        "phone" => $order['phone'],
        "email" => $order['email']
    ],
    "shipping" => [
        "address" => $order['address_line1'] . ', ' . $order['city'] . ', ' . $order['country']
    ],
    "items" => [
        [
            "name" => $product['name'],
            "vendor" => $product['vendor'],
            "price" => $product['price'],
            "qty" => $product['quantity'],
            "total" => $product['total'],
            "image" => $product['image'] ?? 'https://via.placeholder.com/50'
        ]
    ]
]);
