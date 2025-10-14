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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products Dashboard</title>
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
        <link rel="stylesheet" href="../css/vendor_navbar.css">
        <link rel="stylesheet" href="../css/vendor_product.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
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
                <a href="./products.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i
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
        <?php
            require_once '../mydatabase/conn.php';
            $user_id = $_SESSION['user_id'];

            // Determine active tab from URL parameter
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'all';

            // Map tab names to position values
            $tab_position_map = [
                'online' => 'online',
                'pending' => 'pending',
                'offline' => 'offline',
                'draft' => 'draft',
                'all' => 'all'
            ];

            // Get products based on active tab
            if ($active_tab === 'all') {
                $stmt = $conn->prepare("SELECT vp.*, vpi.image_path as primary_image 
                                    FROM vendor_products vp
                                    LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
                                    WHERE vp.user_id = ?");
                $stmt->bind_param("i", $user_id);
            } else {
                $position = $tab_position_map[$active_tab] ?? 'all';
                $stmt = $conn->prepare("SELECT vp.*, vpi.image_path as primary_image 
                                    FROM vendor_products vp
                                    LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
                                    WHERE vp.user_id = ? AND vp.position = ?");
                $stmt->bind_param("is", $user_id, $position);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $products = $result->fetch_all(MYSQLI_ASSOC);
            $total_products = count($products);
            $pending_products = 0;

            foreach ($products as $product) {
                if ($product['position'] === 'pending') {
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

        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="w-1/3">
                    <i class="fa-solid fa-magnifying-glass" style="position: relative; right: -28px;"></i> 
                    <input type="text" id="productSearch" placeholder="Search products" class="border px-4 py-2 rounded border-0 pl-8 border-0 outline-0" />
                </div>
                <div>
                    <div class="w-48 h-[5px] bg-gray-300" style="border-radius: 20px;">
                        <div class="bg-green-500 w-[<?php echo $completion_percentage; ?>%] h-full"></div>
                    </div>
                    <p class="text-center"><span><?php echo $completion_percentage; ?>% </span> Completed</p>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Products</h2>
                <div class="flex gap-4">
                    <button class="bg-red-500 text-white px-4 py-2 rounded">
                        <a href="./new_product.php">Add New Product</a>
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex space-x-8 border-b">
                    <a href="?tab=online" class="pb-2 <?php echo $active_tab === 'online' ? 'border-b-2 border-blue-500' : ''; ?>">Online</a>
                    <a href="?tab=pending" class="pb-2 <?php echo $active_tab === 'pending' ? 'border-b-2 border-blue-500' : ''; ?>">Pending Review</a>
                    <a href="?tab=offline" class="pb-2 <?php echo $active_tab === 'offline' ? 'border-b-2 border-blue-500' : ''; ?>">Offline</a>
                    <a href="?tab=draft" class="pb-2 <?php echo $active_tab === 'draft' ? 'border-b-2 border-blue-500' : ''; ?>">Draft</a>
                    <a href="?tab=all" class="pb-2 <?php echo $active_tab === 'all' ? 'border-b-2 border-blue-500' : ''; ?>">All</a>
                </div>
                <!-- <button class="py-1 px-4 bg-white border-1"><i class="fa-solid fa-filter"></i> &nbsp; filter</button> -->
            </div>

            <!-- Products Table -->
            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Image</th>
                            <th class="p-4">Product Name</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Stock</th>
                            <th class="p-4">Price</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Rating</th>
                            <th class="p-4">Position</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($products) > 0): ?>
                            <?php foreach ($products as $product): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-4"><a href="../product.php?id=<?php echo htmlspecialchars($product['id']); ?>" target="_blank"><?php echo htmlspecialchars($product['id']); ?></a></td>
                                    <td class="p-4">
                                        <a href="../product.php?id=<?php echo htmlspecialchars($product['id']); ?>" target="_blank">
                                        <img style="width: 50px !important; height: 50px !important; border-radius: 10px;" 
                                            src="<?php echo !empty($product['primary_image']) ? htmlspecialchars($product['primary_image']) : '../img/default-product.jpg'; ?>" 
                                            alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        </a>
                                    </td>
                                    <td class="p-4 font-semibold">
                                        <a href="../product.php?id=<?php echo htmlspecialchars($product['id']); ?>" target="_blank">
                                        <p class="h-8 overflow-hidden leading-4 line-clamp-2">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </p>
                                        </a>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($product['category']); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($product['quantity']); ?></td>
                                    <td class="p-4">$<?php echo number_format($product['selling_price'], 2); ?></td>
                                    <td class="p-4">
                                        <?php 
                                        $status_class = '';
                                        $status_text = '';
                                        if ($product['quantity'] > 10) {
                                            $status_class = 'bg-green-100 text-green-700';
                                            $status_text = 'In Stock';
                                        } elseif ($product['quantity'] > 0) {
                                            $status_class = 'bg-yellow-100 text-yellow-700';
                                            $status_text = 'Limited';
                                        } else {
                                            $status_class = 'bg-red-100 text-red-700';
                                            $status_text = 'Out of Stock';
                                        }
                                        ?>
                                        <span class="<?php echo $status_class; ?> px-2 py-1 text-xs rounded">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <?php 
                                        $rating = $product['rating'] ?? 0;
                                        $full_stars = floor($rating);
                                        $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                                        $empty_stars = 5 - $full_stars - $half_star;
                                        
                                        echo str_repeat('<i class="fas fa-star" style="color: gold;"></i>', $full_stars);
                                        echo $half_star ? '<i class="fas fa-star-half-alt" style="color: gold;"></i>' : '';
                                        echo str_repeat('<i class="far fa-star" style="color: gold;"></i>', $empty_stars);
                                        ?>
                                    </td>
                                    <td class="p-4">
                                        <?php 
                                        $position_map = [
                                            'online' => 'Online',
                                            'pending' => 'Pending',
                                            'offline' => 'Offline',
                                            'draft' => 'Draft'
                                        ];
                                        echo $position_map[$product['position']] ?? ucfirst($product['position']);
                                        ?>
                                    </td>
                                    <td class="p-4">
                                        <a href="../product.php?id=<?php echo htmlspecialchars($product['id']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this product?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="p-4 text-center text-gray-500">No products found</td>
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

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <script src="../script/vendor_navbar.js"></script>
    <!-- search  -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("productSearch");
            const tableRows = document.querySelectorAll("table tbody tr");

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

</body>
</html>