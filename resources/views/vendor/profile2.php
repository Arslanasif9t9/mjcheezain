<?php
session_start();
@include '../mydatabase/conn.php';

// Redirect if not logged in or not a vendor
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
} elseif (isset($_SESSION['type']) && $_SESSION['type'] == "customer") {
    header("Location: ../customer/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch vendor data from all tables
$basic_info = [];
$store_details = [];
$address_info = [];

// Get basic info
$stmt = $conn->prepare("SELECT * FROM vendor_basic_info WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$basic_info = $result->fetch_assoc();

// Get store details
$stmt = $conn->prepare("SELECT * FROM vendor_store_details WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$store_details = $result->fetch_assoc();

// Get address info
$stmt = $conn->prepare("SELECT * FROM vendor_address WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$address_info = $result->fetch_assoc();

// Get user email from users table
$stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_email = $result->fetch_assoc()['email'] ?? '';

// Default values if data doesn't exist
$profile_picture = !empty($basic_info['profile_picture']) ? $basic_info['profile_picture'] : '../img/default_profile.webp';
$store_banner = !empty($store_details['store_banner']) ? './uploads/' . $store_details['store_banner'] : '';
$store_logo = !empty($store_details['store_logo']) ? './uploads/' . $store_details['store_logo'] : '';
$full_name = $basic_info['full_name'] ?? 'Not specified';
$store_name = $basic_info['store_name'] ?? 'Not specified';
$phone = $basic_info['phone'] ?? 'Not specified';
$business_type = !empty($store_details['business_type']) ? ucfirst($store_details['business_type']) : 'Not specified';
$store_category = !empty($store_details['store_category']) ? ucfirst($store_details['store_category']) : 'Not specified';
$store_description = $store_details['store_description'] ?? 'No description provided';
$return_policy = $store_details['return_policy'] ?? '';
$shipping_policy = $store_details['shipping_policy'] ?? '';
$return_policy_file = $store_details['return_policy_file'] ?? '';
$shipping_policy_file = $store_details['shipping_policy_file'] ?? '';
$pickup_address = $address_info['pickup_address'] ?? 'Not specified';
$city = !empty($address_info['city']) ? ucfirst($address_info['city']) : 'Not specified';
$area = !empty($address_info['area']) ? ucfirst($address_info['area']) : 'Not specified';
$country = $address_info['country'] ?? 'Pakistan';
$postal_code = $address_info['postal_code'] ?? 'Not specified';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $store_name; ?> - Store Profile</title>
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
  <style>
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

    .verified-badge {
      background-color: #22c55e;
      color: white;
      font-size: 12px;
      padding: 2px 8px;
      border-radius: 8px;
    }
    .profile-header {
      color: white !important;
      position: relative;
      z-index: 100 !important;
    }
    .profile-header::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: url('<?php echo $store_banner ?: '../img/default-banner.jpg'; ?>');
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      z-index: -2 !important;
    }
    .profile-header::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.575);
      z-index: -1 !important;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <button id="btn-side" onclick="navbarToggle(this)"><i class="fas fa-bars m-4"></i></button>
    <aside id="aside" class="w-64 bg-gray-900 text-white p-4">
      <div class="flex flex-col items-center">
        <img class="w-24 h-24 rounded-full object-cover" src="<?php echo $profile_picture; ?>" alt="Profile" />
        <h2 class="mt-4 font-semibold text-xl"><?php echo $full_name; ?></h2>
        <span class="active-button mt-1 bg-green-500 px-2 rounded-full">Active</span>
        <div class="text-yellow-500 mb-4 text-lg"> ★★★★★ </div>
      </div>
      <nav class="space-y-4">
        <a href="./dashboard.php" class="flex items-center gap-2"><i class="fa fa-chart-bar"></i> Dashboard</a>
        <a href="./products.php" class="flex items-center gap-2"><i class="fa fa-box"></i> Products</a>
        <a href="./orders.php" class="flex items-center gap-2"><i class="fa fa-shopping-cart"></i> Orders</a>
        <a href="./chat.php" class="flex items-center gap-2"><i class="fa-brands fa-rocketchat"></i> Live Chat</a>
        <a href="./withdraw.php" class="flex items-center gap-2"><i class="fa fa-wallet"></i> Withdraw</a>
        <a href="./profile.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i
            class="fa-solid fa-user"></i> Profile</a>
        <a href="#" id="logoutBtn" class="flex items-center gap-2"><i class="fas fa-sign-out-alt"></i> Log out</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="bg-gray-100 flex-1 p-6 overflow-y-auto scrollbar-hide">
      <!-- Header -->
      <div class="profile-header p-2 rounded-lg shadow flex items-center justify-center">
        <div class="flex items-center space-x-4">
          <div class="bg-green-400 p-[1px] rounded-full">
            <img src="<?php if (!empty($store_logo)) echo $store_logo; ?>" alt="" class="w-20 h-20 bg-green-400 rounded-full">
            <!-- <svg class="text-white w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M11.3 1.046a1 1 0 00-2.6 0l-.25.638A8.002 8.002 0 003.3 5.3l-.638.25a1 1 0 000 1.9l.638.25a8.002 8.002 0 004.15 4.15l.25.638a1 1 0 001.9 0l.25-.638a8.002 8.002 0 004.15-4.15l.638-.25a1 1 0 000-1.9l-.638-.25a8.002 8.002 0 00-4.15-4.15l-.25-.638zM10 13a7 7 0 110-14 7 7 0 010 14z" />
            </svg> -->
          </div>
          <div>
            <h1 class="text-2xl font-bold"><?php echo $store_name; ?></h1>
            <div class="flex items-center space-x-2 text-green-600">
              <div class="flex space-x-1 text-sm">
                <span>★★★★★</span>
              </div>
              <span class="verified-badge">Verified</span>
            </div>
            <p class="text-white text-sm"><?php echo $city . ', ' . $country; ?></p>
            <button class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
              <a href="./edit-profile.php">Edit Profile</a>
            </button>
          </div>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Person Info -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-xl font-semibold mb-4">Person Info</h3>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Full name:</strong> <?php echo $full_name; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Email:</strong> <?php echo $user_email; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Mobile no:</strong> <?php echo $phone; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Address:</strong> <?php echo $pickup_address; ?>
          </p>
        </div>
        
        <!-- Store Info -->
        <div class="row-span-2 bg-white p-6 rounded-lg shadow">
          <h3 class="text-xl font-semibold mb-4">Store Info</h3>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Type:</strong> <?php echo $business_type; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Category:</strong> <?php echo $store_category; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Website:</strong> 
            <?php if (!empty($store_details['website'])): ?>
              <a href="<?php echo $store_details['website']; ?>" class="text-blue-600 underline" target="_blank">
                <?php echo $store_details['website']; ?>
              </a>
            <?php else: ?>
              Not specified
            <?php endif; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Feature Policy:</strong>
            <span>
              <?php if (!empty($return_policy) || !empty($return_policy_file)): ?>
                <a href="<?php echo !empty($return_policy_file) ? '../uploads/policies/' . $return_policy_file : '#'; ?>" 
                   class="text-blue-600 underline text-sm" <?php echo !empty($return_policy_file) ? 'download' : ''; ?>>
                  Return Policy
                </a>
              <?php else: ?>
                <span class="text-gray-500">Not available</span>
              <?php endif; ?>
              &nbsp;
              <?php if (!empty($shipping_policy) || !empty($shipping_policy_file)): ?>
                <a href="<?php echo !empty($shipping_policy_file) ? '../uploads/policies/' . $shipping_policy_file : '#'; ?>" 
                   class="text-blue-600 underline text-sm" <?php echo !empty($shipping_policy_file) ? 'download' : ''; ?>>
                  Shipping Policy
                </a>
              <?php else: ?>
                <span class="text-gray-500">Not available</span>
              <?php endif; ?>
            </span>
          </p>
          <p class="grid grid-cols-[1fr] border-b-2 boder-gray-100 py-2">
            <strong>Description:</strong> 
            <div class="mt-2"><?php echo nl2br($store_description); ?></div>
          </p>
          <?php if (!empty($store_banner)): ?>
            <p class="grid grid-cols-[1fr] border-b-2 boder-gray-100 py-2">
              <strong>Banner:</strong> 
              <img src="<?php echo $store_banner; ?>" alt="Store Banner" class="mt-2 w-full object-cover">
            </p>
          <?php endif; ?>
          <?php if (!empty($store_logo)): ?>
            <p class="grid grid-cols-[1fr] border-b-2 boder-gray-100 py-2">
              <strong>Logo:</strong> 
              <img src="<?php echo $store_logo; ?>" alt="Store Logo" class="mt-2 h-32 w-32 rounded-full border-4 border-white shadow-md">
            </p>
          <?php endif; ?>
        </div>
        
        <!-- Address -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="text-xl font-semibold mb-4">Address</h3>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Warehouse / Pickup Address:</strong> <?php echo $pickup_address; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>City:</strong> <?php echo $city; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Area / Region:</strong> <?php echo $area; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Country:</strong> <?php echo $country; ?>
          </p>
          <p class="grid grid-cols-[1fr_2fr] border-b-2 boder-gray-100 py-2">
            <strong>Postal Code:</strong> <?php echo $postal_code; ?>
          </p>
        </div>
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
          Yes, Logout
        </button>
      </div>
    </div>
  </div>
  <script src="../script/logout.js"></script>
  <script src="../script/vendor_navbar.js"></script>
</body>
</html>