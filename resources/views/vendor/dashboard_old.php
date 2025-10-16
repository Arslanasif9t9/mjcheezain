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
<!-- graph -->
 <?php
    $vendor_id = $user_id;

    // Get last 6 months order count grouped by month
    $sql = "
        SELECT
            DATE_FORMAT(order_date, '%Y-%m') AS month,
            COUNT(*) AS orders
        FROM orders
        WHERE vendor_id = $vendor_id
        AND order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY month
        ORDER BY month ASC
    ";

    $result = $conn->query($sql);

    // Prepare arrays for months and order counts
    $months = [];
    $orders = [];

    // Initialize all last 6 months with 0 orders by default
    for ($i = 5; $i >= 0; $i--) {
        $m = date('Y-m', strtotime("-$i months"));
        $months[$m] = 0;
    }

    // Fill the months with actual order counts from DB
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $months[$row['month']] = (int)$row['orders'];
        }
    }

    // Format months to short names like Aug, Sep...
    $labels = [];
    $data = [];
    foreach ($months as $month => $count) {
        $labels[] = date('M', strtotime($month . '-01'));
        $data[] = $count;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard UI</title>
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
    <link rel="stylesheet" href="../css/vendor_dashboard.css">
    <link rel="stylesheet" href="../css/vendor_chat.css">
    <link rel="stylesheet" href="../css/vendor_navbar.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 600px;
            max-height: 400px;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
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
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- <i class="fas fa-bars"></i>
        <i class="fas fa-times"></i> -->
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
                <a href="./dashboard.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i class="fa fa-chart-bar"></i> Dashboard</a>
                <a href="./products.php" class="flex items-center gap-2"><i
                        class="fa fa-box"></i>
                    Products</a>
                <a href="./orders.php" class="flex items-center gap-2"><i class="fa fa-shopping-cart"></i> Orders</a>
                <!-- <a href="./chat.php" class="flex items-center gap-2"><i class="fa-brands fa-rocketchat"></i> Live
                    Chat</a> --> 
                <a href="./withdraw.php" class="flex items-center gap-2"><i class="fa fa-wallet"></i> Withdraw</a>
                <a href="./profile.php" class="flex items-center gap-2"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="#" id="logoutBtn" class="flex items-center gap-2"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="bg-gray-100 flex-1 p-6 overflow-y-auto scrollbar-hide">
            <!-- Header -->
            <div class="header flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold"> Overview</h1>
                <i class='fa-solid fa-magnifying-glass relative right-[-7.5%]'></i> 
                <input type="text"
                    placeholder="Search product, order or customer..."
                    class="px-4 py-2 pl-12 border rounded border-0 outline-0 w-[70%] rounded-full" />
                <div class="text-xl">
                    <!-- <button class="relative" id="notification-button">
                        <i class="fa-solid fa-bell m-4 z-0"></i>
                        <span class="noti-num bg-red-500 text-white">2</span>

                        <div id="notification-dropdown"
                            class="divide-y hidden absolute left-[-200px] top-[45px] bg-gray-100 text-sm text-left border-2 rounded-5">
                            <div class="p-2 hover:bg-white cursor-pointer">
                                <img src="../img/Arslan.jpg" alt="">
                                <div class="">
                                    <p class="font-semibold">New Order <span class="text-sm text-gray-400 float-right">5
                                            min.
                                            ago</span></p>
                                    <p class="text-sm text-gray-600">You received a new order #12345.</p>
                                </div>
                            </div>
                            <div class="p-2 hover:bg-white cursor-pointer">
                                <img src="../img/Arslan.jpg" alt="">
                                <div>
                                    <p class="font-semibold">New Customer <span
                                            class="text-sm text-gray-400 float-right">5 min.
                                            ago</span></p>
                                    <p class="text-sm text-gray-600">yes sir, we can sell that to you</p>
                                </div>
                            </div>
                            <div class="p-2 hover:bg-white cursor-pointer">
                                <img src="../img/Arslan.jpg" alt="">
                                <div>
                                    <p class="font-semibold">Order Status <span
                                            class="text-sm text-gray-400 float-right">5 min.
                                            ago</span></p>
                                    <p class="text-sm text-gray-600">yes sir, we can sell that to you</p>
                                </div>
                            </div>
                            <p class="p-2 hover:bg-white btn-show text-center">See more <i class="fa fa-caret-down"></i>
                            </p>
                        </div>
                    </button> -->
                    <a href="./profile.php"><button class="bg-white w-10 h-10 rounded-full"><i class="fa-solid fa-user"></i></button></a>
                </div>
            </div>

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="col-span-2 bg-white shadow rounded p-4">
                    <div class="font-semibold mb-2">Monthly Orders - Last 6 Months Sales</div>
                    <canvas id="ordersChart"></canvas>
                </div>
                <div class="bg-white p-4 rounded shadow text-center  rounded-[20px] flex justify-center items-center">
                    <div>
                        <p class="text-2xl font-bold">Current Balance</p>
                        <p class="text-3xl font-bold p-4 w-max mx-auto" style="border-bottom: 2px solid black;">
                            <?php
                                $balance = $conn->query("SELECT total_balance FROM vendor_balance WHERE user_id = $user_id;")->fetch_assoc()['total_balance'];
                                echo $balance;
                            ?>
                            <span class="text-sm">PKR</span>
                        </p>
                        <button class="mt-4 px-4 py-2 bg-green-500 text-white rounded"><a
                                href="./withdraw.php">Withdraw Now</a></button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="v-stats grid grid-cols-1 md:grid-cols-3 gap-4 text-center mb-6">
                <a href="./products.php">
                    <div class="bg-white p-4 rounded shadow">
                        <i class="fa-solid fa-box-open text-green-900 text-4xl row-span-2"></i>
                        <p class="text-2xl font-bold text-black">
                            <?php echo $conn->query("SELECT COUNT(*) AS total FROM vendor_products WHERE user_id = $user_id")->fetch_assoc()['total']; ?>
                        </p>
                        <p class="text-sm text-gray-500">All Product</p>
                    </div>
                </a>
                <a href="./products.php">
                    <div class="bg-white p-4 rounded shadow">
                        <i class="fa-solid fa-boxes-packing text-blue-400 text-4xl row-span-2"></i>
                        <p class="text-lg font-bold">
                            <?php echo $conn->query("SELECT COUNT(*) AS total FROM orders WHERE vendor_id = $user_id AND fulfillment = 'delivered'")->fetch_assoc()['total']; ?>
                        </p>
                        <p class="text-sm text-gray-500"> Total Sales</p>
                    </div>
                </a>
                <a href="./orders.php">
                    <div class="bg-white p-4 rounded shadow">
                        <i class="fa-solid fa-cart-plus text-blue-900 text-4xl row-span-2"></i>
                        <p class="text-lg font-bold">
                            <?php echo $conn->query("SELECT COUNT(*) AS total FROM orders WHERE vendor_id = $user_id")->fetch_assoc()['total']; ?>
                        </p>
                        <p class="text-sm text-gray-500">New Order</p>
                    </div>
                </a>
            </div>

            <!-- Recent Sold & Top Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Sold -->
                 <!-- Recent Sold -->
                        <div class="lg:col-span-2 bg-white p-4 rounded shadow">
                            <h2 class="text-lg font-bold mb-4">Recent Sold</h2>
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-sm text-gray-600">
                                        <th>Image</th>
                                        <th class="py-2">Product</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm text-gray-700">
                <?php
                    $vendor_id = $user_id; // Change as needed
                    // Query to fetch 3 most recent orders with product and customer info
                    $sql = "
                        SELECT 
                            o.id AS order_id,
                            p.name AS product_name,
                            p.category AS product_category,
                            o.total_amount,
                            o.order_date,
                            c.first_name AS customer_name,
                            o.fulfillment,
                            pi.image_path
                        FROM orders o
                        JOIN vendor_products p ON o.product_id = p.id
                        JOIN users u ON o.user_id = u.user_id
                        LEFT JOIN customer_profile c ON o.user_id = c.user_id
                        LEFT JOIN vendor_product_images pi ON pi.product_id = p.id AND pi.is_primary = TRUE
                        WHERE o.vendor_id = $vendor_id 
                        ORDER BY o.order_date DESC
                        LIMIT 3
                    ";

                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        echo '';
                        
                        while ($row = $result->fetch_assoc()) {
                            // Format date as dd/mm/YYYY
                            $dateFormatted = date('d/m/Y', strtotime($row['order_date']));

                            // Status color class
                            $status = strtolower($row['fulfillment']);
                            $colorClass = match ($status) {
                                'pending' => 'text-yellow-600',
                                'processing' => 'text-purple-600',
                                'shipped' => 'text-yellow-600',
                                'delivered' => 'text-green-600',
                                'cancelled' => 'text-red-600',
                                default => 'text-gray-600',
                            };

                            // Image fallback
                            $imgSrc = $row['image_path'] ? htmlspecialchars($row['image_path']) : 'uploads/default_product.webp';

                            echo '<tr class="border-t">
                                <td class="py-2"><img style="width:50px; height:50px; border-radius:10px;" src="'. $imgSrc .'" alt="Product Image"></td>
                                <td class="py-2">'.htmlspecialchars($row['product_name']).'</td>
                                <td>'.htmlspecialchars($row['product_category']).'</td>
                                <td>'.number_format($row['total_amount'], 2).' TK</td>
                                <td>'.$dateFormatted.'</td>
                                <td>'.htmlspecialchars($row['customer_name'] ?: 'N/A').'</td>
                                <td><span class="'. $colorClass .'">'. ucfirst($status) .'</span></td>
                            </tr>';
                        }

                        echo '';
                    } else {
                        echo "<p>No recent orders found.</p>";
                    }
                    ?>
                </tbody></table></div>


                <!-- Top Categories -->
                <?php
                    $vendor_id = $user_id; // Change to your vendor's user_id

                    $sql = "
                        SELECT 
                            p.name,
                            COUNT(o.id) AS order_count
                        FROM vendor_products p
                        LEFT JOIN orders o ON p.id = o.product_id AND o.vendor_id = $vendor_id
                        WHERE p.user_id = $vendor_id
                        GROUP BY p.id, p.name
                        ORDER BY order_count DESC
                        LIMIT 6
                    ";

                    $result = $conn->query($sql);

                    echo '<!-- Top Categories -->
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="text-lg font-bold mb-4">Top 5 Sales Categories</h2>
                        <ul class="space-y-2 text-sm text-gray-700">';

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Escape output to prevent XSS
                            $product_name = htmlspecialchars($row['name']);
                            $order_count = (int)$row['order_count'];

                            echo '<li class="flex justify-between"><span>' . $product_name . '</span><span class="font-bold">' . $order_count . '</span></li>';
                        }
                    } else {
                        echo '<li>No products found.</li>';
                    }

                    echo '  </ul>
                    </div>';
                    ?>

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
                <button id="confirmLogoutBtn" type="button"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    Yes, Logout
                </button>
            </div>
        </div>
    </div>
    <!-- Logout Confirmation Button -->
    <form id="logoutForm" action="../logout.php" method="POST" style="display: none;">
        <input type="hidden" name="logout" value="1">
    </form>
    <script src="../script/logout.js"></script>

    <script src="../script/vendor_navbar.js"></script>
    <script src="../script/notification.js"></script>

    <!-- PHP use  -->
     <script>
        document.getElementById("confirmLogoutBtn").addEventListener("click", function () {
            console.log("logout");
            document.getElementById("logoutForm").submit();
        });
    </script>
    <!-- graph script  -->
    <script>
        const ctx = document.getElementById('ordersChart').getContext('2d');

        const ordersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Orders',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(70, 130, 180, 0.7)', // steelblue
                    borderColor: 'rgba(70, 130, 180, 1)',
                    borderWidth: 1,
                    barPercentage: 0.5
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
        </script>
</body>

</html>