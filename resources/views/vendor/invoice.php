<?php
// Start session and check if user is logged in as vendor
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'vendor') {
    die("Unauthorized access");
}

// Database connection (from .env — never hardcode credentials)
$servername = function_exists('env') ? env('DB_HOST', 'localhost') : 'localhost';
$username = function_exists('env') ? env('DB_USERNAME', 'root') : 'root';
$password = function_exists('env') ? env('DB_PASSWORD', '') : '';
$dbname = function_exists('env') ? env('DB_DATABASE', 'cheezaindb') : 'cheezaindb';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order ID from request
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Verify the order belongs to this vendor
$vendor_id = $_SESSION['user_id'];
$verify_query = "SELECT id FROM orders WHERE id = ? AND vendor_id = ?";
$stmt = $conn->prepare($verify_query);
$stmt->bind_param("ii", $order_id, $vendor_id);
$stmt->execute();
$verify_result = $stmt->get_result();

if ($verify_result->num_rows === 0) {
    die("Order not found or unauthorized access");
}

// Fetch order details
$order_query = "SELECT o.*, 
               u.full_name AS customer_name, 
               u.email AS customer_email,
               u.phone AS customer_phone,
               cp.profile_image AS customer_image,
               vp.name AS product_name,
               vp.description AS product_description,
               vp.selling_price AS unit_price,
               vp.delivery_charges,
               vpi.image_path AS product_image,
               ca.address_line1, ca.address_line2, ca.city, ca.state, ca.zip_code, ca.country,
               ca.full_name AS shipping_name, ca.phone AS shipping_phone
               FROM orders o
               JOIN users u ON o.user_id = u.user_id
               LEFT JOIN customer_profile cp ON o.user_id = cp.user_id
               JOIN vendor_products vp ON o.product_id = vp.id
               LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
               JOIN customer_addresses ca ON o.shipping_address_id = ca.id
               WHERE o.id = ?";

$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .invoice-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            overflow-y: auto;
        }
        .invoice-content {
            background-color: white;
            margin: 2rem auto;
            width: 80%;
            max-width: 800px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- Invoice Button in your orders table -->
<button onclick="showInvoice(<?= $order_id ?>)" class="text-blue-500 hover:text-blue-700">
    <i class="fa-solid fa-circle-info text-xl"></i>
</button>

<!-- Invoice Modal -->
<div id="invoiceModal" class="invoice-modal">
    <div class="invoice-content">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Order Invoice</h2>
                    <p class="text-gray-600">#<?= $order_id ?></p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Order Date: <?= date('M d, Y', strtotime($order['order_date'])) ?></p>
                    <p class="text-gray-600">Last Updated: <?= date('M d, Y', strtotime($order['updated_at'])) ?></p>
                </div>
            </div>

            <!-- Customer and Shipping Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Customer Information</h3>
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="<?= $order['customer_image'] ?: 'https://i.pravatar.cc/40?img=1' ?>" 
                             class="w-10 h-10 rounded-full">
                        <span class="font-medium"><?= htmlspecialchars($order['customer_name']) ?></span>
                    </div>
                    <p class="text-gray-600"><?= htmlspecialchars($order['customer_email']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['customer_phone']) ?></p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Shipping Address</h3>
                    <p class="text-gray-600"><?= htmlspecialchars($order['shipping_name']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['address_line1']) ?></p>
                    <?php if (!empty($order['address_line2'])): ?>
                        <p class="text-gray-600"><?= htmlspecialchars($order['address_line2']) ?></p>
                    <?php endif; ?>
                    <p class="text-gray-600"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['zip_code']) ?>, <?= htmlspecialchars($order['country']) ?></p>
                    <p class="text-gray-600">Phone: <?= htmlspecialchars($order['shipping_phone']) ?></p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h3 class="font-semibold text-lg mb-4">Order Items</h3>
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <div class="p-4 border-b flex items-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-md overflow-hidden mr-4">
                            <?php if (!empty($order['product_image'])): ?>
                                <img src="<?= $order['product_image'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image fa-lg"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium"><?= htmlspecialchars($order['product_name']) ?></h4>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars(substr($order['product_description'], 0, 100)) ?>...</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">Quantity: <?= $order['quantity'] ?></p>
                            <p class="font-medium">Rs. <?= number_format($order['unit_price'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-lg mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>Rs. <?= number_format($order['unit_price'] * $order['quantity'], 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Charges:</span>
                        <span>Rs. <?= number_format($order['delivery_charges'], 2) ?></span>
                    </div>
                    <div class="flex justify-between border-t pt-2 font-bold">
                        <span>Total Amount:</span>
                        <span>Rs. <?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-lg mb-2">Fulfillment Status</h3>
                    <form id="fulfillmentForm">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <div class="flex items-center space-x-2">
                            <select name="fulfillment" class="border rounded px-3 py-2 flex-1">
                                <option value="pending" <?= $order['fulfillment'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="unfulfillment" <?= $order['fulfillment'] == 'unfulfillment' ? 'selected' : '' ?>>Unfulfilled</option>
                                <option value="processing" <?= $order['fulfillment'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['fulfillment'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['fulfillment'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['fulfillment'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2">Payment Status</h3>
                    <span class="badge <?= $order['status'] == 'paid' ? 'bg-green-500' : 'bg-yellow-500' ?> text-white">
                        <?= ucfirst($order['status']) ?>
                    </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <button onclick="hideInvoice()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Close
                </button>
                <button onclick="updateFulfillment()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Fulfillment
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Show invoice modal
    function showInvoice(orderId) {
        $('#invoiceModal').fadeIn();
    }

    // Hide invoice modal
    function hideInvoice() {
        $('#invoiceModal').fadeOut();
    }

    // Update fulfillment status
    function updateFulfillment() {
        $.ajax({
            url: 'update_fulfillment.php',
            method: 'POST',
            data: $('#fulfillmentForm').serialize(),
            success: function(response) {
                alert('Fulfillment status updated successfully');
                location.reload(); // Refresh to see changes
            },
            error: function() {
                alert('Failed to update fulfillment status');
            }
        });
    }

    // Close modal when clicking outside
    $(document).mouseup(function(e) {
        var container = $(".invoice-content");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            hideInvoice();
        }
    });
</script>
</body>
</html>

<?php $conn->close(); ?>