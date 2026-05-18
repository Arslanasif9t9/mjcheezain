<?php
    session_start();
    require_once './mydatabase/conn.php';

    // Check if user is logged in
    $user_id = $_SESSION['user_id'];
    $product_id = $_GET['productId'] ?? null;
    if (!isset($_SESSION['user_id']) || $_SESSION['type'] == "vendor") {
        $_SESSION['error'] = "Order failed: first login as customer";
        header("Location: product.php?id=$product_id");
        exit();
    }


    if (!$product_id) {
        $_SESSION['error'] = "Order failed: Product not found";
        header("Location: products.php");
        exit();
    }

    // Get product details from database
    $stmt = $conn->prepare("SELECT vp.*, vi.image_path, u.username 
                        FROM vendor_products vp
                        LEFT JOIN vendor_product_images vi ON vp.id = vi.product_id AND vi.is_primary = TRUE
                        LEFT JOIN users u ON vp.user_id = u.user_id
                        WHERE vp.id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        header("Location: products.php");
        exit();
    }

    // Get vendor details
    $stmt = $conn->prepare("SELECT vbi.store_name, vbi.profile_picture, va.city, va.area
                        FROM vendor_basic_info vbi
                        LEFT JOIN vendor_address va ON vbi.user_id = va.user_id
                        WHERE vbi.user_id = ?");
    $stmt->bind_param("i", $product['user_id']);
    $stmt->execute();
    $vendor = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $quantity = (int)$_POST['quantity'];
        $address_id = (int)$_POST['address_id'];
        // $payment_method = $conn->real_escape_string($_POST['payment_method']);
        
        // Validate quantity doesn't exceed stock
        if ($quantity > $product['quantity']) {
            $_SESSION['error'] = "Quantity exceeds available stock.";
            header("Location: buy.php?productId=$product_id");
            exit();
        }
        
        // Calculate total
        $subtotal = $quantity * $product['selling_price'];
        $total_amount = $subtotal + $product['delivery_charges'];
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Create order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, vendor_id, quantity, subtotal, delivery_charges, total_amount, shipping_address_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiidddi", 
            $user_id, 
                $product_id, 
                $product['user_id'],
                $quantity,
                $subtotal,
                $product['delivery_charges'],
                $total_amount,
                $address_id
            );
            $stmt->execute();
            $order_id = $conn->insert_id;
            // $_SESSION['msg'] = $_FILES['receipt']['name'];
            $stmt->close();
            
            // Update product stock
            $stmt = $conn->prepare("UPDATE vendor_products SET quantity = quantity - ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();
            $stmt->close();
            
            // Record payment if bank transfer
            // if ($payment_method === 'bank_transfer') {
                $bank_name = $conn->real_escape_string($_POST['bank_name']);
                $account_holder = $conn->real_escape_string($_POST['account_holder']);
                $transaction_ref = $conn->real_escape_string($_POST['transaction_ref']);
                $transaction_date = $conn->real_escape_string($_POST['transaction_date']);
                
                // Handle file upload
                $receipt_path = '';
                if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = './customer/uploads/payments/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
                    $file_name = "receipt_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
                    $receipt_path = $upload_dir . $file_name;
                    
                    if (!move_uploaded_file($_FILES['receipt']['tmp_name'], $receipt_path)) {
                        throw new Exception("Failed to upload receipt.");
                    }
                }
                
                $stmt = $conn->prepare("INSERT INTO payments (order_id, bank_name, account_holder, transaction_ref, transaction_date, amount, receipt_path) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssds", 
                    $order_id, 
                    $bank_name, 
                    $account_holder, 
                    $transaction_ref, 
                    $transaction_date, 
                    $total_amount,
                    $receipt_path
                );
                $stmt->execute();
                $stmt->close();
            // }
            
            // Record activity
            $stmt = $conn->prepare("INSERT INTO customer_recent_activity (user_id, activity_type, title, value, points) 
                                VALUES (?, 'order_placed', ?, ?, ?)");
            $activity_title = "Order #ORD-" . str_pad($order_id, 6, '0', STR_PAD_LEFT) . " placed";
            $activity_points = "$" . number_format($total_amount, 2);
            $stmt->bind_param("issd", $user_id, $activity_title, $product['name'], $activity_points);
            $stmt->execute();
            $stmt->close();
            
            $conn->commit();
            
            // $_SESSION['error'] = "jbjh";
            // $_SESSION['error'] = isset($_FILES['receipt']);
            $_SESSION['success'] = "Order placed successfully! Your order ID is #$order_id";
            header("Location: ./customer/orders.php");
            // header("Location: buy.php?productId=$product_id");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Order failed: " . $e->getMessage();
            header("Location: buy.php?productId=$product_id");
            exit();
        }
    }

    // Get customer addresses
    $addresses = [];
    $stmt = $conn->prepare("SELECT * FROM customer_addresses WHERE user_id = ? ORDER BY is_default DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $addresses[] = $row;
    }
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now - <?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
<form action="" method="POST">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <!-- <pre> -->
                    <span class="block sm:inline"><?= $_SESSION['msg']; ?></span>
                <!-- </pre> -->
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">Complete Your Purchase</h1>
                <p class="text-blue-100">Review your order details and make payment</p>
            </div>
            
            <!-- Order Summary -->
            <div class="px-6 py-4 border-b">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Product Info -->
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold mb-3">Product Details</h2>
                        <div class="flex items-start gap-4">
                            <img src="vendor/<?= htmlspecialchars($product['image_path'] ?? '../images/default-product.jpg') ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="w-24 h-24 object-cover rounded-lg border">
                            <div>
                                <h3 class="font-medium"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="text-gray-600 text-sm">Sold by: <?= htmlspecialchars($vendor['store_name'] ?? 'Unknown') ?></p>
                                <p class="text-gray-600 text-sm">Location: <?= htmlspecialchars($vendor['city'] ?? '') ?>, <?= htmlspecialchars($vendor['area'] ?? '') ?></p>
                                
                                <div class="flex items-center mt-2">
                                    <button type="button" onclick="updateQuantity(-1)" class="px-2 py-1 bg-gray-200 rounded-l">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" min="1" max="<?= $product['quantity'] ?>" value="1" 
                                           class="w-12 text-center border-t border-b border-gray-300">
                                    <button type="button" onclick="updateQuantity(1)" class="px-2 py-1 bg-gray-200 rounded-r">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <span class="ml-2 text-sm text-gray-500">Available: <?= $product['quantity'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Summary -->
                    <div class="md:w-1/3">
                        <h2 class="text-lg font-semibold mb-3">Order Summary</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal (<span id="summary-quantity">1</span> item):</span>
                                <span>$<span id="summary-subtotal"><?= number_format($product['selling_price'], 2) ?></span></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Delivery:</span>
                                <span>$<span id="summary-delivery"><?= number_format($product['delivery_charges'], 2) ?></span></span>
                            </div>
                            <div class="flex justify-between text-lg font-bold mt-3 pt-3 border-t">
                                <span>Total:</span>
                                <span class="text-blue-600">$<span id="summary-total"><?= number_format($product['selling_price'] + $product['delivery_charges'], 2) ?></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Shipping Address -->
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold mb-3">Shipping Address</h2>
                <?php if (empty($addresses)): ?>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <p class="text-yellow-800">You don't have any saved addresses. <a href="addresses.php" class="text-blue-600 hover:underline">Add an address</a> to continue.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($addresses as $address): ?>
                            <div class="address-card bg-white rounded-lg border <?= $address['is_default'] ? 'border-blue-500 bg-blue-50' : 'border-gray-300' ?> p-4">
                                <div class="flex justify-between">
                                    <h3 class="font-medium"><?= htmlspecialchars($address['address_type']) ?></h3>
                                    <?php if ($address['is_default']): ?>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Default</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-700 mt-2"><?= htmlspecialchars($address['full_name']) ?></p>
                                <p class="text-gray-700"><?= htmlspecialchars($address['address_line1']) ?></p>
                                <?php if (!empty($address['address_line2'])): ?>
                                    <p class="text-gray-700"><?= htmlspecialchars($address['address_line2']) ?></p>
                                <?php endif; ?>
                                <p class="text-gray-700"><?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?> <?= htmlspecialchars($address['zip_code']) ?></p>
                                <p class="text-gray-700"><?= htmlspecialchars($address['country']) ?></p>
                                <p class="text-gray-700 mt-2">Phone: <?= htmlspecialchars($address['phone']) ?></p>
                                <div class="mt-3">
                                    <input type="radio" name="address_id" value="<?= $address['id'] ?>" 
                                           id="address_<?= $address['id'] ?>" 
                                           <?= $address['is_default'] ? 'checked' : '' ?>
                                           class="address-radio">
                                    <label for="address_<?= $address['id'] ?>" class="ml-2 text-sm">Select this address</label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4">
                        <a href="addresses.php" class="text-blue-600 hover:underline">
                            <!-- <i class="fas fa-plus mr-1"></i> Add new address -->
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Payment Method -->
            <div class="px-6 py-4">
                <!-- <h2 class="text-lg font-semibold mb-3">Payment Method</h2>
                <div class="space-y-4">
                    <div class="flex items-center border border-gray-300 rounded-lg p-4">
                        <input type="radio" id="payment_cod" name="payment_method" value="cod" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <label for="payment_cod" class="ml-3 block text-sm font-medium text-gray-700">
                            <span class="font-bold">Cash on Delivery</span>
                            <span class="block text-gray-500">Pay when you receive the product</span>
                        </label>
                    </div>
                    
                    <div class="flex items-center border border-gray-300 rounded-lg p-4">
                        <input type="radio" id="payment_bank" name="payment_method" value="bank_transfer" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                        <label for="payment_bank" class="ml-3 block text-sm font-medium text-gray-700">
                            <span class="font-bold">Bank Transfer</span>
                            <span class="block text-gray-500">Transfer money directly to our bank account</span>
                        </label>
                    </div>
                </div> -->
                
                <!-- Bank Transfer Details (shown when selected) -->
                <div id="bank-details" class="mt-6 bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-700 mb-3"><i class="fas fa-university mr-2"></i>Bank Account Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded border">
                            <div class="space-y-2">
                                <p><span class="font-medium">Bank Name:</span> National Commerce Bank</p>
                                <p><span class="font-medium">Account Name:</span> Cheezain Marketplace</p>
                                <p><span class="font-medium">Account Number:</span> 1234567890</p>
                                <p><span class="font-medium">SWIFT/BIC:</span> NCBKUS33</p>
                            </div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <div class="space-y-2">
                                <p><span class="font-medium">Amount to Transfer:</span> 
                                    $<span id="payment-amount"><?= number_format($product['selling_price'] + $product['delivery_charges'], 2) ?></span>
                                </p>
                                <p><span class="font-medium">Reference:</span> 
                                    <span class="bg-blue-100 px-2 py-1 rounded">ORD-<?= rand(100000,999999) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="mt-6">
                            <h4 class="font-medium mb-2">Payment Confirmation</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Bank Name</label>
                                    <input type="text" name="bank_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                                    <input type="text" name="account_holder" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Reference</label>
                                    <input type="text" name="transaction_ref" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Date</label>
                                    <input type="date" name="transaction_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Payment Receipt</label>
                                    <div class="mt-1 flex items-center">
                                        <label for="receipt-upload" class="cursor-pointer">
                                            <span class="px-4 py-2 block m-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                                                <i class="fas fa-cloud-upload-alt mr-2"></i>Choose File
                                            </span>
                                            <input id="receipt-upload" name="receipt" type="file" class="hidden" accept="image/*,.pdf">
                                        </label>
                                        <span class="ml-2 text-sm text-gray-500" id="file-name">No file chosen</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Acceptable formats: JPG, PNG, PDF (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                        <!-- Terms and Submit -->
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="terms-agreement" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                                <label for="terms-agreement" class="ml-2 block text-sm text-gray-700">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                </label>
                            </div>
                            
                            <div class="mt-6 flex justify-between">
                                <a href="product.php?id=<?= $product_id ?>" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 focus:outline-none">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Product
                                </a>
                                <button type="submit" id="submit-btn" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 focus:outline-none">
                                    Place Order <i class="fas fa-check ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Quantity adjustment
        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            const maxQuantity = <?= $product['quantity'] ?>;
            let newQuantity = parseInt(quantityInput.value) + change;
            
            // Validate quantity range
            newQuantity = Math.max(1, Math.min(maxQuantity, newQuantity));
            quantityInput.value = newQuantity;
            
            // Update amounts
            updateAmounts(newQuantity);
        }
        
        // Update all amounts when quantity changes
        function updateAmounts(quantity) {
            const price = <?= $product['selling_price'] ?>;
            const delivery = <?= $product['delivery_charges'] ?>;
            const subtotal = price * quantity;
            const total = subtotal + delivery;
            
            document.getElementById('summary-quantity').textContent = quantity;
            document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('summary-total').textContent = total.toFixed(2);
            document.getElementById('payment-amount').textContent = total.toFixed(2);
        }
        
        // Show selected file name
        document.getElementById('receipt-upload').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });
        
        // Toggle bank details based on payment method
        // document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        //     radio.addEventListener('change', function() {
        //         document.getElementById('bank-details').style.display = 
        //             this.value === 'bank_transfer' ? 'block' : 'none';
        //     });
        // });
        
        // Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            // Validate address is selected
            if (!document.querySelector('input[name="address_id"]:checked')) {
                e.preventDefault();
                alert('Please select a shipping address');
                return;
            }
            
            // Validate payment method is selected
            // if (!document.querySelector('input[name="payment_method"]:checked')) {
            //     e.preventDefault();
            //     alert('Please select a payment method');
            //     return;
            // }
            
            // Validate terms are agreed
            if (!document.getElementById('terms-agreement').checked) {
                e.preventDefault();
                alert('Please agree to the terms and conditions');
                return;
            }
            
            // Additional validation for bank transfer
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            if (paymentMethod === 'bank_transfer') {
                const bankName = document.querySelector('input[name="bank_name"]').value;
                const accountHolder = document.querySelector('input[name="account_holder"]').value;
                const transactionRef = document.querySelector('input[name="transaction_ref"]').value;
                const transactionDate = document.querySelector('input[name="transaction_date"]').value;
                const receipt = document.querySelector('input[name="receipt"]').files[0];
                
                if (!bankName || !accountHolder || !transactionRef || !transactionDate || !receipt) {
                    e.preventDefault();
                    alert('Please fill in all bank transfer details');
                    return;
                }
            }
        });
    </script>
</body>
</html>