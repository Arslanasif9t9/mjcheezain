<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    }
    elseif (isset($_SESSION['type']) && $_SESSION['type'] == "customer") {
        header("Location: ../customer/dashboard.php");
    }

    require_once '../mydatabase/conn.php';
    $user_id = $_SESSION['user_id'];
    // Get basic info
    $stmt = $conn->prepare("SELECT * FROM vendor_basic_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $basic_info = $result->fetch_assoc();
    // Default values if data doesn't exist
    $profile_picture = !empty($basic_info['profile_picture']) ? $basic_info['profile_picture'] : '../img/default_profile.webp';
    $full_name = $basic_info['full_name'] ?? 'Not specified';
?>

<?php
    $total_orders_query = "SELECT 
                COUNT(*) AS total_orders,
                SUM(CASE WHEN fulfillment = 'unfulfillment' THEN 1 ELSE 0 END) AS unfulfilled_orders,
                SUM(CASE WHEN fulfillment = 'pending' THEN 1 ELSE 0 END) AS pending_orders,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS paid_orders,
                SUM(CASE WHEN status = 'unpaid' THEN 1 ELSE 0 END) AS unpaid_orders
                FROM orders 
                WHERE vendor_id = ? AND vendor_visible = TRUE";
    $stmt = $conn->prepare($total_orders_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_count = $result->fetch_assoc();

    // Determine active tab from URL parameter
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'all';

    // Base query for orders
    $query = "SELECT o.*, 
            u.full_name AS customer_name, 
            cp.profile_image AS customer_image,
            vp.name AS product_name,
            vp.selling_price AS unit_price,
            ((vp.original_price * o.quantity) - vp.selling_price) AS profit
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            LEFT JOIN customer_profile cp ON o.user_id = cp.user_id
            JOIN vendor_products vp ON o.product_id = vp.id
            WHERE o.vendor_id = ? AND o.vendor_visible = TRUE";

    // Modify query based on active tab
    switch ($active_tab) {
        case 'unpaid':
            $query .= " AND o.status = 'unpaid'";
            break;
        case 'unfulfilled':
            $query .= " AND o.fulfillment = 'unfulfillment'";
            break;
        case 'all':
            // No additional filter needed
            break;
        default: // 'active'
            $query .= " AND o.fulfillment NOT IN ('delivered', 'cancelled')";
            break;
    }

    $query .= " ORDER BY o.order_date DESC";

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();

    // Get counts for each tab
    $count_query = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN fulfillment NOT IN ('delivered', 'cancelled') THEN 1 ELSE 0 END) AS active,
                    SUM(CASE WHEN status = 'unpaid' THEN 1 ELSE 0 END) AS unpaid,
                    SUM(CASE WHEN fulfillment = 'unfulfillment' THEN 1 ELSE 0 END) AS unfulfilled
                    FROM orders 
                    WHERE vendor_id = ? AND vendor_visible = TRUE";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("i", $user_id);
    $count_stmt->execute();
    $counts = $count_stmt->get_result()->fetch_assoc();


            $per_sql = $conn->query("SELECT * FROM orders");
            $total_products = mysqli_num_rows($per_sql);
            $pending_products = 0;
            // echo $orders;

            foreach ($per_sql as $product) {
                if ($product['status'] === 'unpaid') {
                    $pending_products++;
                }
            }

            // If there are no products, prevent division by zero
            if ($total_products > 0) {
                $completion_percentage = 100 - (($pending_products / $total_products) * 100);
                $completion_percentage = round($completion_percentage); // Round to whole number
            } else {
                $completion_percentage = 0;
            }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders Dashboard</title>
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./CDN tailwind.js"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Custom animation for modal */
    #logoutModal {
      animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }



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
    <link rel="stylesheet" href="../css/vendor_order.css">
    <link rel="stylesheet" href="../css/vendor_navbar.css">
    <link rel="stylesheet" href="../css/vendor_product.css">
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <button id="btn-side" onclick="navbarToggle(this)"><i class="fas fa-bars m-4"></i></button>
        <aside id="aside" class="w-64 bg-gray-900 text-white p-4">
            <div class="flex flex-col items-center">
                <img class="w-24 h-24 rounded-full object-cover" src="<?php echo $profile_picture; ?>" alt="Profile" />
                <h2 class="mt-4 font-semibold text-xl"><?php echo $full_name; ?></h2>
                <?php if ($basic_info['profile_visibility']) 
                        echo "<span class='active-button mt-1 bg-green-500 px-2 rounded-full'> Active </span>";
                    else echo "<span class='active-button mt-1 bg-red-500 px-2 rounded-full'> Close </span>";
                ?>
                <div class="text-yellow-500 mb-4 text-lg"> ★★★★★ </div>
            </div>
            <nav class="space-y-4">
                <a href="./dashboard.php" class="flex items-center gap-2"><i class="fa fa-chart-bar"></i> Dashboard</a>
                <a href="./products.php" class="flex items-center gap-2"><i
                        class="fa fa-box"></i>
                    Products</a>
                <a href="./orders.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i class="fa fa-shopping-cart"></i> Orders</a>
                <!-- <a href="./chat.php" class="flex items-center gap-2"><i class="fa-brands fa-rocketchat"></i> Live
                    Chat</a> -->
                <a href="./withdraw.php" class="flex items-center gap-2"><i class="fa fa-wallet"></i> Withdraw</a>
                <a href="./profile.php" class="flex items-center gap-2"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="#" id="logoutBtn" class="flex items-center gap-2"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="w-1/3">
                    <i class="fa-solid fa-magnifying-glass" style="position: relative; right: -28px;"></i> 
                    <input id="productSearch"
                        type="text" placeholder="Search orders" class="border px-4 py-2 rounded border-0 pl-8" />
                </div>
                <div>
                    <div class="w-48 h-[5px] bg-gray-300" style="border-radius: 20px;">
                        <div class="bg-green-500 w-[<?= $completion_percentage ?>%] h-full"></div>
                    </div>
                    <p class="text-center"><span><?= $completion_percentage ?>% </span> Completed</p>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold ">Orders list</h2>
                <div class="flex gap-4">
                    <!-- <div class="p-2"><i class="fa-solid fa-ellipsis-vertical" style="transform: rotate(90deg);"></i> </div> -->
                    <!-- <button class="bg-red-500 text-white px-4 py-2 rounded">Create order</button> -->
                </div>
            </div>

            <div class="o-cards grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded card">
                    <p class="text-gray-600">Totall Orders</p>
                    <p class="text-2xl font-semibold"><?php echo $total_count['total_orders'] ?></p>
                </div>
                <div class="bg-white p-4 rounded card">
                    <p class="text-gray-600">Unfulfilled</p>
                    <p class="text-2xl font-semibold"><?php echo $total_count['unfulfilled_orders'] ?></p>
                </div>
                <div class="bg-white p-4 rounded card">
                    <p class="text-gray-600">Pending Rece...</p>
                    <p class="text-2xl font-semibold"><?php echo $total_count['pending_orders'] ?></p>
                </div>
                <div class="bg-white p-4 rounded card">
                    <p class="text-gray-600">Unpaid</p>
                    <p class="text-2xl font-semibold"><?php echo $total_count['unpaid_orders'] ?></p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex justify-between items-center mb-4">
                <div class="mb-4 flex space-x-8 border-b">
                    <a href="?tab=all" class="pb-2 <?= $active_tab == 'all' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                        All orders (<?= $counts['total'] ?>)
                    </a>
                    <a href="?tab=active" class="pb-2 <?= $active_tab == 'active' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                        Active (<?= $counts['active'] ?>)
                    </a>
                    <a href="?tab=unpaid" class="pb-2 <?= $active_tab == 'unpaid' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                        Unpaid (<?= $counts['unpaid'] ?>)
                    </a>
                    <a href="?tab=unfulfilled" class="pb-2 <?= $active_tab == 'unfulfilled' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600' ?>">
                        Unfulfilled (<?= $counts['unfulfilled'] ?>)
                    </a>
                </div>
                <!-- <button class="py-1 px-4 bg-white border border-gray-300 rounded">
                    <i class="fa-solid fa-filter"></i> &nbsp; filter
                </button> -->
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow">
                    <thead>
                        <tr class="text-left text-sm text-gray-500 border-b">
                            <th class="px-4 py-2">Order ID</th>
                            <th class="px-4 py-2">Created</th>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Fulfillment</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Profit</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Updated</th>
                            <th class="px-4 py-2">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php if ($orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 text-blue-600 font-medium"><?= $order['id'] ?></td>
                                    <td class="px-4 py-2"><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                                    <td class="px-4 py-2 flex items-center space-x-2">
                                        <img class="w-6 h-6 rounded-full" src="../customer/<?= $order['customer_image'] ?: 'https://i.pravatar.cc/40?img=1' ?>">
                                        <span><?= htmlspecialchars($order['customer_name']) ?></span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <?php
                                        $fulfillment_class = [
                                            'unfulfillment' => 'bg-yellow-400',
                                            'pending' => 'bg-purple-600',
                                            'processing' => 'bg-blue-500',
                                            'shipped' => 'bg-indigo-500',
                                            'delivered' => 'bg-green-500',
                                            'cancelled' => 'bg-red-500'
                                        ][$order['fulfillment']] ?? 'bg-gray-500';
                                        ?>
                                        <span class="<?= $fulfillment_class ?> text-white px-2 py-1 rounded text-xs">
                                            <?= ucfirst($order['fulfillment']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">Rs. <?= number_format($order['total_amount'], 2) ?></td>
                                    <td class="px-4 py-2">Rs. <?= number_format($order['profit'], 2) ?></td>
                                    <td class="px-4 py-2">
                                        <span class="<?= $order['status'] == 'paid' ? 'bg-green-500' : 'bg-yellow-500' ?> text-white px-2 py-1 rounded text-xs">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <?= date('Y-m-d') == date('Y-m-d', strtotime($order['updated_at'])) ? 'Today' : date('M j, Y', strtotime($order['updated_at'])) ?>
                                    </td>
                                    <td class="p-4 flex gap-2">
                                        <!-- <i class="fa-solid fa-pen-to-square"></i> -->
                                        <a href="?order_id=<?php echo $order['id']; ?>" class="text-blue-500 hover:text-blue-700">
                                            <i class="fa-solid fa-circle-info text-xl"></i>
                                        </a>
                                        <!-- <a href="delete_order.php?id=<?= $order['id'] ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a> -->
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-gray-500">No orders found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>









    <!-- logout modal -->
  <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">Are you sure you want to logout?</h2>
      <p class="text-gray-600 mb-6">You'll need to sign in again to access your account.</p>
      <div class="flex justify-end space-x-3">
        <button id="cancelBtn"
          class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
          Cancel
        </button>
        <button id="confirmLogout" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
          <a href="../logout.php">Yes, Logout</a>
        </button>
      </div>
    </div>
  </div>
  <script src="../script/logout.js"></script>

  <!-- search  -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("productSearch");
        const tableRows = document.querySelectorAll("table tbody tr");
    console.log(searchInput);
        searchInput.addEventListener("keyup", function () {
            const searchTerm = searchInput.value.toLowerCase();

            tableRows.forEach((row) => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>


<?php
    // Verify the order belongs to this vendor
    $vendor_id = $_SESSION['user_id'];
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    $verify_query = "SELECT id FROM orders WHERE id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("ii", $order_id, $vendor_id);
    $stmt->execute();
    $verify_result = $stmt->get_result();

    if ($verify_result->num_rows === 0) {
        die("Order not found or unauthorized access");
    }

    // Fetch order details
    $order_invoice = "SELECT o.*, 
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
                ca.full_name AS shipping_name, ca.phone AS shipping_phone,
                p.transaction_ref AS payment_id
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                LEFT JOIN customer_profile cp ON o.user_id = cp.user_id
                JOIN vendor_products vp ON o.product_id = vp.id
                LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
                JOIN customer_addresses ca ON o.shipping_address_id = ca.id
                JOIN payments p ON o.id = p.order_id
                WHERE o.id = ?";

    $stmt = $conn->prepare($order_invoice);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
?>
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
                        <img src="../customer/<?= $order['customer_image'] ?: 'https://i.pravatar.cc/40?img=1' ?>" 
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
                    <div class="flex justify-between border-t pt-2 font-bold">
                        <span>Payment ID:</span>
                        <span><?= substr($order['payment_id'], 0, -5) . str_repeat('*', min(5, strlen($order['payment_id']))) ?></span>
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
                    <h3 class="font-semibold text-lg mb-2">Get Payment</h3>
                    <form id="paymentFo" action="update_payment.php" method="POST" class="flex items-center gap-2">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <div class="flex items-center space-x-2">
                            <span class="badge <?= $order['status'] == 'paid' ? 'bg-green-500' : 'bg-yellow-500' ?> text-white">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <input type="text" name="payment_id" class="border rounded px-3 py-2 flex-1 w-full" placeholder="Enter Payment ID">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Paid
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between space-x-3">
                <button onclick="updateFulfillment()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Fulfillment
                </button>
                <button onclick="hideInvoice()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Close
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
    setTimeout(() => {
        showInvoice(<?php echo $order_id; ?>);
    }, 500);

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
                // alert('Fulfillment status updated successfully');
                location.reload(); // Refresh to see changes
            },
            error: function() {
                alert('Failed to update fulfillment status');
            }
        });
    }

    // Update payment status
    function updatePayment() {
        $.ajax({
            url: 'update_payment.php',
            method: 'POST',
            data: $('#paymentForm').serialize(),
            success: function(response) {
                // alert('Fulfillment status updated successfully');
                location.reload(); // Refresh to see changes
            },
            error: function() {
                alert('Failed to update payment status');
            }
        });
    }

    // Close modal when clicking outside
    // $(document).mouseup(function(e) {
    //     var container = $(".invoice-content");
    //     if (!container.is(e.target) && container.has(e.target).length === 0) {
    //         hideInvoice();
    //     }
    // });
</script>

    <script src="../script/vendor_navbar.js"></script>
</body>

</html>