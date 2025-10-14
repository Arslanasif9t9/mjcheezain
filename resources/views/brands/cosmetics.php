<?php
session_start();
// signup 
@include '../mydatabase/conn.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $_POST['submit'] == '1') {
    $userType = trim($_POST['type']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $re_password = $_POST['repassword'];

    // Validation
    if (empty($name)) $error = "Full name is required";
    elseif (empty($email)) $error = "Email is required";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = "Invalid email format";
    elseif (empty($phone)) $error = "Phone number is required";
    elseif (empty($password)) $error = "Password is required";
    elseif ($password !== $re_password) $error = "Passwords don't match";

    if (empty($error)) {
        try {
            // Check if phone or email already exists using prepared statement
            $stmt = $conn->prepare("SELECT * FROM users WHERE phone = ? OR email = ?");
            $stmt->bind_param("ss", $phone, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Phone or email already exists";
            } 
            else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // First insert basic user info to get the auto-incremented ID
                $insert = $conn->prepare("INSERT INTO users (type, full_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
                $insert->bind_param("sssss",$userType , $name, $email, $phone, $hashed_password);
                $insert->execute();
                
                // Get the newly created user ID
                $userID = $insert->insert_id;
                
                // Extract first name and clean it
                $firstName = explode(' ', trim($name))[0]; // Gets first part of name
                $cleanFirstName = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName));
                
                // Generate username (firstname + ID)
                $username = $cleanFirstName . $userID;
                
                // Update the user record with the generated username
                $update = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
                $update->bind_param("si", $username, $userID);
                $update->execute();


                
                // Registration successful
                if ($userType == "vendor") {
                    // insert vendor_basic_detail table
                    $insert = $conn->prepare("INSERT INTO vendor_basic_info (user_id, full_name, phone, email) VALUES (?, ?, ?, ?)");
                    $insert->bind_param("isss",$userID , $name, $phone, $email);
                    $insert->execute();
                    $conn->query("INSERT INTO vendor_balance (user_id, total_balance) VALUES ($userID, 0.00);");
                    // header("Location: http://localhost/phpCode/cheezain/vendor/edit-profile.php");
                }
                else {
                    // Assuming $name contains the full name (e.g., "John Doe")
                    $nameParts = explode(' ', $name, 2); // Split into 2 parts (first name & last name)

                    $firstName = $nameParts[0]; // First part is first name
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : ''; // Second part is last name (if exists)

                    // Update the query to use first_name and last_name
                    $insert = $conn->prepare("INSERT INTO customer_profile (user_id, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)");
                    $insert->bind_param("issss", $userID, $firstName, $lastName, $email, $phone);
                    $insert->execute();
                    // header("Location: http://localhost/phpCode/cheezain/customer/edit-profile.php");
                }
                // exit();
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<?php
// login 
$loginError = "";
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitLogin']) && $_POST['submitLogin'] == '1') {
    $loginInput = trim($_POST['id']);
    $password = $_POST['password'];

    // Validation
    if (empty($loginInput)) $loginError = "Username or email is required";
    elseif (empty($password)) $loginError = "Password is required";

    if (empty($loginError)) {
        try {
            // Check if login input matches username or email
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $loginInput, $loginInput);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Start session and store user data
                    // session_start();
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['type'] = $user['type']; // 'vendor' or 'customer'
                    
                    // Redirect based on user type
                    if ($user['type'] == "vendor") {
                        header("Location: http://localhost/phpCode/cheezain/vendor/dashboard.php");
                    } else {
                        header("Location: http://localhost/phpCode/cheezain/customer/dashboard.php");
                    }
                    exit();
                } else {
                    $loginError = "Invalid password";
                }
            } else {
                $loginError = "User not found";
            }
        } catch (Exception $e) {
            $loginError = "Database error: " . $e->getMessage();
        }
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cheezain</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../CDN tailwind.js"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Google Fonts Link For Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="../css/login&signup.css">
    <script src="../script/login&signup.js" defer></script>
    <style>
        :root {
            --nav-height:120px;
            --nav-height-n: -120px;
        }

        .hero-bg {
            position: relative;
            top: var(--nav-height-n);
            z-index: -2;
            overflow: hidden;
        }

        .video-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            object-fit: cover;
        }

        .hero-bg::before {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-color: rgba(0,0,0,0.5);
        }

        .hero-header header {
            position: relative !important;
        }

        .header-heo {
            position: sticky !important;
            top: 0 !important;
            left: 0 !important;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .hero-bg .flex-col > div {
                width: 100%;
            }
            
            .hero-bg .flex-col .flex-col {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
            }
            
            .hero-bg .flex-col input,
            .hero-bg .flex-col select {
                flex: 1 1 calc(50% - 4px);
                min-width: 0;
                font-size: 12px;
                padding: 8px;
            }
            
            .hero-bg .flex-col button {
                flex: 1 1 100%;
            }
            
            h1 {
                font-size: 24px;
            }
            
            p {
                font-size: 14px;
                margin-bottom: 16px;
            }
        }

        @media (max-width: 480px) {
            .hero-bg .flex-col input,
            .hero-bg .flex-col select {
                flex: 1 1 100%;
            }
            
            .hero-bg .flex-col {
                gap: 8px;
            }
        }
      .pb-con a img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
      }


      /* Product Grid Styles */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="bg-gray-200">
    <!-- alert  -->
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $error ?></span>
            <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php elseif ($loginError): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb0" role="alert">
            <span class="block sm:inline"><?= $loginError ?></span>
            <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Header  -->
    <header class="header-heo" style="background: transparent; height: var(--nav-height); transition: all 0.3s ease; z-index: 400;">
        <!-- Navigation Bar -->
        <nav class="bg-transparent shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-24" style="height: 100%;">
                    <!-- Mobile menu button - Left side -->
                    <div class="md:hidden flex items-center">
                        <button type="button" id="nav-btn" class="text-white hover:text-orange-600 focus:outline-none absolute right-5" style="z-index: 1010;">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Logo - Centered -->
                    <div class="flex-shrink-0 flex items-center mx-auto md:mx-0">
                        <img src="../img/logo-html.png" alt="PakWheels Logo" class="h-20 w-auto logo-img" style="height: 120px; position: absolute; top: 100px; left: 100px; transition: all 0.3s ease brightness-200">
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex md:items-center md:space-x-6 text-white mx-20" style="z-index: 199;">
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Engines</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Bumpers</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Suspension</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Wheels</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Vehicals</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Videos</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Blog</a>
                        <a href="#" class="hover:text-orange-600 px-3 py-2 text-sm font-medium">Support</a>
                    </div>

                    <!-- Spacer for mobile to balance the hamburger menu -->
                    <div class="md:hidden w-8"></div>
                </div>
            </div>
        </nav>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 bg-opacity-95 transform -translate-x-full transition-transform duration-300 ease-in-out z-40 md:hidden" style="z-index: 1011;">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                <div class="text-white font-semibold text-lg">Menu</div>
                <button id="close-sidebar" class="text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-4 py-6 bg-gray-900" style="z-index: 1012;">
                <nav class="flex flex-col space-y-4">
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200" style="z-index: 2022;">
                        <span class="ml-3">Engines</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Bumpers</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Suspension</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Wheels</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Vehicals</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Videos</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Blog</span>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-orange-500 hover:bg-gray-800 px-4 py-3 rounded-md transition duration-200 flex items-center">
                        <span class="ml-3">Support</span>
                    </a>
                </nav>
            </div>
        </div>
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navBtn = document.getElementById('nav-btn');
            const closeSidebar = document.getElementById('close-sidebar');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            navBtn.addEventListener('click', function() {
                mobileSidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            closeSidebar.addEventListener('click', function() {
                mobileSidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            });

            sidebarOverlay.addEventListener('click', function() {
                mobileSidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });
    </script>

    <style>
        /* Smooth transitions */
        #mobile-sidebar {
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        /* Logo positioning remains unchanged */
        .logo-img {
            height: 120px;
            position: absolute;
            top: 100px;
            left: 100px;
            transition: all 0.3s ease;
        }

        /* Mobile menu items animation */
        #mobile-sidebar a {
            transform: translateX(-10px);
            /* opacity: 0; */
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #mobile-sidebar.show a {
            transform: translateX(0);
            opacity: 1;
        }

        /* Staggered animation for menu items */
        #mobile-sidebar a:nth-child(1) { transition-delay: 0.1s; }
        #mobile-sidebar a:nth-child(2) { transition-delay: 0.15s; }
        #mobile-sidebar a:nth-child(3) { transition-delay: 0.2s; }
        #mobile-sidebar a:nth-child(4) { transition-delay: 0.25s; }
        #mobile-sidebar a:nth-child(5) { transition-delay: 0.3s; }
        #mobile-sidebar a:nth-child(6) { transition-delay: 0.35s; }
        #mobile-sidebar a:nth-child(7) { transition-delay: 0.4s; }
        #mobile-sidebar a:nth-child(8) { transition-delay: 0.45s; }

        @media (max-width: 767px) {
            .header-heo {
                height: 80px;
            }
            .logo-img {
                height: 80px;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    </style>

    <div class="hero-header hero-bg" style="z-index: 10;">
        <!-- Video Background -->
        <video autoplay muted loop playsinline class="video-bg">
            <source src="./auto-video.mp4" type="video/mp4">
            <!-- Fallback image if video doesn't load -->
            <img src="../img/default-banner.jpg" alt="Fallback background">
        </video>
        
        <!-- Hero Section -->
        <div class="flex flex-col items-center justify-center text-center w-[100vw] h-[100vh] pb-0" style="z-index: 20;">
            <h1 class="text-white text-3xl md:text-4xl mb-1 px-4">Discover Authentic Beauty Products</h1>
            <p class="mb-8 text-gray-300 px-4">Premium skincare & makeup delivered to your doorstep in just 3–5 days</p>
            <div class="w-full max-w-4xl px-4">
                <div class="bg-white rounded-md p-2 grid grid-cols-[5fr_3fr_3fr_3fr] md:grid-cols-7 gap-2 text-black" style="z-index: 30;">
                    <input type="text" id="model" name="model" placeholder="Product Name"
                        class="flex-1 md:col-span-2 p-2 rounded border border-gray-300 min-w-0 text-sm md:text-base">
                    <select class="flex-1 md:col-span-2 p-2 rounded border border-gray-300 min-w-0 text-sm md:text-base">
                        <option>All Cities</option>
                        <option>Lahore</option>
                        <option>Karachi</option>
                        <option>Islamabad</option>
                    </select>
                    <select class="flex-1 md:col-span-2 p-2 rounded border border-gray-300 min-w-0 text-sm md:text-base">
                        <option>Price Range</option>
                        <option>Below 10 Lakh</option>
                        <option>10 - 20 Lakh</option>
                        <option>Above 20 Lakh</option>
                    </select>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm md:text-base whitespace-nowrap">
                        🔍 Search
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Results Section -->
    <section class="current-sol-items bg-white mt-[-60px] mb-4 p-4 max-w-[78vw] mx-auto" id="search-section" style="display: none;">
        <!-- Results will be dynamically inserted here by JavaScript -->
        <div class="text-center py-8 text-gray-500">
            Search for Cosmetics to see results here
        </div>
    </section>

    <div class="blur-bg-overlay"></div>
    <div class="form-popup">
        <span class="close-btn material-symbols-rounded">close</span>
        <div class="form-box login">
            <div class="form-details">
                <h2>Welcome Back</h2>
                <p>Please log in using your personal information to stay connected with us.</p>
            </div>
            <div class="form-content">
                <h2>LOGIN</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="input-field">
                        <input type="text" name="id" required>
                        <label>Email or username</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password">
                        <label>Password</label>
                    </div>
                    <a href="#" class="forgot-pass-link">Forgot password?</a>
                    <button type="submit" name="submitLogin" value="1">Log In</button>
                </form>
                <div class="bottom-link">
                    Don't have an account?
                    <a href="#" id="signup-link">Signup</a>
                </div>
            </div>
        </div>
        <div class="form-box signup">
            <div class="form-details">
                <h2>Create Account</h2>
                <p>To become a part of our community, please sign up using your personal information.</p>
            </div>
            <div class="form-content">
                <h2>SIGNUP</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input id="userType" type="hidden" name="type" value="">
                    <div class="input-field">
                        <input type="text" name="name">
                        <label>Full Name</label>
                    </div>
                    <div class="input-field">
                        <input type="email" name="email">
                        <label>Enter your email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password">
                        <label>Create password</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="repassword">
                        <label>Re-password</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="phone">
                        <label>Phone</label>
                    </div>
                    <div class="policy-text">
                        <input type="checkbox" id="policy" name="policy" required>
                        <label for="policy">
                            I agree the
                            <a href="#" class="option">Terms & Conditions</a>
                        </label>
                    </div>
                    <button type="submit" name="submit" value="1">Sign Up</button>
                    <!-- <p style="color: red; font-size: 15px; margin-top: -18px;"> <php echo $error; ?></p> -->
                </form>
                <div class="bottom-link">
                    Already have an account?
                    <a href="#" id="login-link">Login</a>
                </div>
            </div>
        </div>
    </div>

    <?php
        // Function to display products by category
        function displayProductsByCategory($conn, $category) {
            // Query to get products with their primary image
            $sql = "SELECT vp.*, vpi.image_path 
                    FROM vendor_products vp
                    LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
                    WHERE vp.subcategory = ? AND vp.position = 'approved'
                    ORDER BY vp.updated_at DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $timeAgo = timeAgo($row['updated_at']);
                    $defaultImage = "../img/default_img.png"; // Default image if no product image
                    
                    echo '<a href="product-details.php?id='.$row['id'].'" class="d-inline-block w-[13.6vw]">';
                    echo '<img src="../vendor/'.(!empty($row['image_path']) ? $row['image_path'] : $defaultImage).'" width="225px" height="230px" alt="'.$row['name'].'">';
                    echo '<p class="my-1 h-8 overflow-hidden leading-4 line-clamp-2">'.$row['name'].'</p>';
                    echo '<p class="my-1">Instant decision <span class="text-[#c50] font-bold">'.$row['selling_price'].' PKR</span></p>';
                    echo '<p class="my-1">Successful bid <span>'.$timeAgo.'</span></p>';
                    echo '</a>';
                }
            } else {
                echo '<p>No products found in this category.</p>';
            }
        }
        // Function to display popular auto parts (top 5 by order count)
        function displayPopularAutoParts($conn) {
            // Query to get top 5 auto parts products with highest order count
            $sql = "SELECT vp.*, vpi.image_path, COUNT(o.id) as order_count
                    FROM vendor_products vp
                    LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
                    LEFT JOIN orders o ON vp.id = o.product_id
                    WHERE vp.category = 'Cosmetics Items' 
                    AND vp.position = 'approved'
                    GROUP BY vp.id
                    ORDER BY order_count DESC, vp.updated_at DESC
                    LIMIT 4";
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $timeAgo = timeAgo($row['updated_at']);
                    $defaultImage = "../img/default_img.png"; // Default image if no product image
                    
                    echo '<a href="../product.php?id='.$row['id'].'" class="w-56 flex-shrink-0">';
                    echo '<img src="../vendor/'.(!empty($row['image_path']) ? $row['image_path'] : $defaultImage).'" alt="'.$row['name'].'" style="width: 225px; height: 230px">';
                    echo '<p class="my-1 h-8 overflow-hidden leading-4 line-clamp-2">'.$row['name'].'</p>';
                    echo '<p class="my-1">Instant decision <span class="text-[#c50] font-bold">'.$row['selling_price'].' PKR</span></p>';
                    // echo '<p class="my-1">Orders: <span class="font-bold">'.$row['order_count'].'</span></p>';
                    echo '<p class="my-1">Last updated <span>'.$timeAgo.'</span></p>';
                    echo '</a>';
                }
            } else {
                // Fallback to recently updated if no orders yet
                displayProductsByCategory($conn, 'Auto Parts & Accessories', 5);
            }
        }

        // Function to calculate time ago
        function timeAgo($datetime) {
            $time = strtotime($datetime);
            $current = time();
            $diff = $current - $time;
            
            $intervals = array(
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            );
            
            foreach ($intervals as $seconds => $label) {
                if ($diff >= $seconds) {
                    $value = floor($diff / $seconds);
                    return $value . ' ' . $label . ($value == 1 ? '' : 's') . ' ago';
                }
            }
            
            return 'just now';
        }
    ?>

    <div class="main-con mt-[-100px]">
        <!-- asidebar  -->
        <aside class="row-span-2">
            <!-- Category  -->
            <div class="s-category bg-white mb-4 p-4">
                <div class="category-head">
                    <p class="font-bold inline">Category List</p>
                    <p class="float-right text-blue"></p>
                </div>
                <hr>
                <ul>
                    <li>Car Tools</li>
                    <li>Auto Parts</li>
                    <li>Perfumes & Fragrances</li>
                    <li>Fitness & Gym Equipment</li>
                    <li>Women's Fashion</li>
                    <li>Men's Accessories</li>
                    <li>Clothing & Apparel</li>
                    <li>Mobile Accessories</li>
                    <li>Home & Living</li>
                    <li>Gifts & General Items</li>
                </ul>
                <!-- <hr> -->
                <button class="btn-show" onclick="show(this, 'Category')">See more <i
                        class="fa fa-caret-down"></i></button>
            </div>
        </aside>
        <!-- popular-brands -->
        <section class="popular-brands bg-white ml4 p-4 -[78vw] m-auto">
            <h2 class="font-bold">Popular Cosmetics</h2>
            <div class="pupular-con relative">
                <!-- <button
                    class="left-btn absolute left-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i
                        class="fa-solid fa-arrow-left text-xl"></i></button> -->
                <div class="w-full border-[red] border-0 " style="width: 78vw !important;">
                    <div class="flex gap-8 w-full" style="width: 100% !important;">
                        <?php displayPopularAutoParts($conn); ?>
                    </div>
                </div>
                <!-- <button class="right-btn absolute right-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i class="fa-solid fa-arrow-right text-xl"></i></button> -->
            </div>
        </section>
        <!-- Whitening cream  -->
        <section class="current-sold-items col-span-2 bg-white p-4 -[78vw] m-auto">
            <h2 class="font-bold mb-4">Whitening Cream</h2>
            <div class="pupular-con relative">
                <button
                    class="left-btn absolute left-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i
                        class="fa-solid fa-arrow-left text-xl"></i></button>
                <div class="w-100 border-[red] border-0 overflow-hidden" style="width: 78vw !important;">
                    <div class="test flex gap-8 overflow-x-auto w-max">
                        <?php displayProductsByCategory($conn, "Whitening cream"); ?>
                    </div>
                </div>
                <button
                    class="right-btn absolute right-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i
                        class="fa-solid fa-arrow-right text-xl"></i></button>
            </div>
        </section>
        <!-- cosmetics official  -->
        <section class="current-sold-items col-span-2 bg-white mt0 p-4 -[78vw] m-auto">
            <h2 class="font-bold mb-4">cosmetics official</h2>
            <div class="pupular-con relative">
                <button
                    class="left-btn absolute left-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i
                        class="fa-solid fa-arrow-left text-xl"></i></button>
                <div class="w-100 border-[red] border-0 overflow-hidden" style="width: 78vw !important;">
                    <div class="test flex gap-8 overflow-x-auto w-max">
                        <?php displayProductsByCategory($conn, "cosmetics official"); ?>
                    </div>
                </div>
                <button
                    class="right-btn absolute right-[-15px] top-[50%] translate-y-[-50%] bg-white  border-black border-2 rounded-full w-[40px] h-[40px]"><i
                        class="fa-solid fa-arrow-right text-xl"></i></button>
            </div>
        </section>
    </div>




        <!-- Footer -->
    <div id="footer" data-message="../"></div>
    <script>
        const footerElement = document.getElementById('footer');
        const message = footerElement.dataset.message;

        fetch('../mycomp/footer.html')
            .then(res => res.text())
            .then(data => {
            // Replace {{message}} in footer.html with actual message
            footerElement.innerHTML = data.replace(/{{message}}/g, message);
            });
    </script>

    <!-- Alpine JS for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Mobile menu toggle
        document.querySelector('#nav-btn').addEventListener('click', function () {
            const mobileMenu = document.querySelector('#nav-mob');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    <script src="../script/javascript.js"></script>

    <!-- for php user tyep  -->
     <script>
        function userType(type) {
            document.getElementById('userType').value = type;
        }
     </script>
     <!-- search  -->
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchButton = document.querySelector('.bg-green-600');
            const modelInput = document.getElementById('model');
            const citySelect = document.querySelector('select:nth-of-type(1)');
            const priceSelect = document.querySelector('select:nth-of-type(2)');
            
            searchButton.addEventListener('click', function() {
                performSearch();
            });
            
            // Also allow search on Enter key in model input
            modelInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            function performSearch() {
                document.querySelector('.main-con').style.margin = '0';

                const searchTerm = modelInput.value.trim();
                const city = citySelect.value;
                const priceRange = priceSelect.value;
                console.log(searchTerm, city, priceRange);
                
                // Show loading state
                searchButton.innerHTML = '⌛';
                document.getElementById('search-section').style.display = "block";
                
                fetch('search.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        model: searchTerm,
                        city: city,
                        priceRange: priceRange
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Reset button icon
                    searchButton.innerHTML = '🔍';
                    
                    if (data.success) {
                        displayResults(data.products);
                    } else {
                        displayNoResults();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchButton.innerHTML = '🔍';
                    displayError();
                });
            }
            
            function displayResults(products) {
                const resultsContainer = document.querySelector('.current-sold-items');
                
                if (!resultsContainer) {
                    console.error('Results container not found');
                    return;
                }
                
                // Update the section heading
                resultsContainer.innerHTML = `
                    <h2 class="font-bold mb-4">Search Results (${products.length} items found)</h2>
                    <div class="products-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        ${products.map(product => createProductCard(product)).join('')}
                    </div>
                `;
            }
            
            function createProductCard(product) {
                const defaultImage = '../img/pickup.png';
                const imagePath = product.image_path || defaultImage;
                
                return `
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                        <a href="product-details.php?id=${product.id}" class="block">
                            <img src="../vendor/${imagePath}" alt="${product.name}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1">${product.brand} ${product.model}</h3>
                                <p class="text-gray-600 mb-2">${product.name}</p>
                                <p class="text-sm text-gray-500 mb-1">Condition: ${product.condition}</p>
                                <p class="text-sm text-gray-500 mb-1">Location: ${product.location}</p>
                                <p class="text-sm text-gray-500 mb-1">Seller: ${product.store_name}</p>
                                <p class="text-sm text-gray-500 mb-2">Rating: ${product.rating ? '★'.repeat(Math.round(product.rating)) : 'Not rated'}</p>
                                <p class="text-lg font-bold text-green-600">PKR ${product.selling_price.toLocaleString()}</p>
                            </div>
                        </a>
                    </div>
                `;
            }
            
            function displayNoResults() {
                const resultsContainer = document.querySelector('.current-sold-items');
                resultsContainer.innerHTML = `
                    <h2 class="font-bold mb-4">No Results Found</h2>
                    <p class="text-gray-600">Try adjusting your search filters</p>
                `;
            }
            
            function displayError() {
                const resultsContainer = document.querySelector('.current-sold-items');
                resultsContainer.innerHTML = `
                    <h2 class="font-bold mb-4">Error</h2>
                    <p class="text-gray-600">There was an error processing your search. Please try again.</p>
                `;
            }
        });
     </script>
     <script src="../script/navbar_scroll.js"></script>
</body>

</html>