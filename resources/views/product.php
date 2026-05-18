<!-- signup  -->
<?php
    session_start();
    @include './mydatabase/conn.php';

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

<!-- login  -->
<?php
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

<!-- Check if product ID is provided -->
<?php
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: ../index.php");
        exit();
    }

    $product_id = $_GET['id'];

    try {
        // Get product details
        $product_stmt = $conn->prepare("
            SELECT vp.*, u.full_name as seller_name, u.profile_picture as seller_image 
            FROM vendor_products vp
            JOIN vendor_basic_info u ON vp.user_id = u.user_id
            WHERE vp.id = ?
        ");
        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        
        if ($product_result->num_rows === 0) {
            throw new Exception("Product not found");
        }
        
        $product = $product_result->fetch_assoc();

        // Get product images
        $images_stmt = $conn->prepare("SELECT * FROM vendor_product_images WHERE product_id = ? ORDER BY is_primary DESC");
        $images_stmt->bind_param("i", $product_id);
        $images_stmt->execute();
        $images = $images_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get product cards
        $cards_stmt = $conn->prepare("SELECT * FROM vendor_product_cards WHERE product_id = ?");
        $cards_stmt->bind_param("i", $product_id);
        $cards_stmt->execute();
        $cards = $cards_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get product faults
        $faults_stmt = $conn->prepare("SELECT * FROM vendor_product_faults WHERE product_id = ?");
        $faults_stmt->bind_param("i", $product_id);
        $faults_stmt->execute();
        $faults = $faults_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
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
    <link rel="stylesheet" href="./css/style.css">

    <!-- Google Fonts Link For Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="./css/login&signup.css">
    <script src="./script/login&signup.js" defer></script>

    <style>
        .price-text {
            font-size: 28px;
            font-weight: bold;
            color: #e60012;
        }

        .bid-button {
            background: linear-gradient(to bottom, #ffdf00, #ffbb00);
            border: 1px solid #e6b422;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        }

        .buynow-button {
            background: linear-gradient(to bottom, #ff6b6b, #e60012);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        /* Hover State - Darker gradient and stronger shadow */
        .buynow-button:hover {
            background: linear-gradient(to bottom, #ff5252, #cc0011);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Active State - Pressed down effect */
        .buynow-button:active {
            transform: translateY(1px);
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
            background: linear-gradient(to bottom, #e60012, #ff6b6b); /* Reverse gradient for pressed effect */
        }

        /* Optional: Ripple effect for modern UI */
        .buynow-button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .buynow-button:focus:not(:active)::after {
            animation: ripple 0.6s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .addcart-button {
            background: linear-gradient(to bottom, #4CAF50, #2E7D32);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .addcart-button:hover {
            background: linear-gradient(to bottom, #43A047, #1B5E20);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .addcart-button:active {
            transform: translateY(1px);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .timer {
            background-color: #f8f8f8;
            border: 1px solid #e5e5e5;
        }

        .seller-rating {
            color: #e60012;
        }

        .tab-active {
            border-bottom: 3px solid #e60012;
            color: #e60012;
            font-weight: bold;
        }

        .description-text {
            line-height: 1.8;
        }
        .hidden {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="../css/customer_product.css">
</head>

<body class="bg-gray-100 text-gray-800 font-sans">
    <?php if ($error): ?>
        <div class="bg-red-500 text-white p-4 rounded" role="alert" style="position: absolute; left: 20px; top: 25px; visibility: hdden; z-index: 1000;">
            <?php echo $error; ?>
        </div>
    <?php elseif ($loginError): ?>
        <div class="bg-red-500 text-white p-4 rounded" role="alert" style="position: absolute; left: 20px; top: 25px; visibility: hdden; z-index: 1000;">
            <?php echo $loginError; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    <header class="bg-gary-600 text-white shadow-lg h-24 header-front" style="background: black;">
        <div class="container mx-auto items-center justify-between px-20 py-0">
            <!-- Center - Logo -->
            <div class="m-auto grid grid-cols-3">
                <img src="./img/full_logo-removebg-preview.png" alt="Company Logo" class="h-12 w-64">
                <p class="justify-self-center align-self-center font-bold text-3xl">cheezain</p>
                <p></p>
            </div>

            <!-- Left side - AutoParts heading -->
            <div class="text-left">
                <p class="text-md font-bold"><a href="./autoparts.php" style="color: whitesmoke; text-decoration: none;"> <i class="fas fa-tools"></i> AUTOPARTS</a></p>
            </div>
            
            
            <!-- Right side - Perfume heading -->
            <!-- <div class="text-right"> -->
                <!-- <h1 class="text-2xl font-bold text-pink-400">PERFUME</h1> -->
            <!-- </div> -->
        </div>
    </header>

    <!-- Search bar  -->
    <div class="flex items-center justify-between space-x-4 mx-20 mt-4 rounded-[4px]">
        <div class="flex items-center w-full max-w-2xl">
            <!-- Search Bar Container -->
            <div class="flex search-bar w-full max-w-2xl">
                <input type="text" id="searchInput" placeholder="Explore autoparts, Perfume, Fashionable...."
                    class="w-[66vw] px-4 py-2 outline-none input rounded-[4px]" />
                <select id="categorySelect" class="px-2 border-l border-gray-300 text-sm text-gray-700">
                    <option>All Categories</option>
                    <option>Auto Parts</option>
                    <option>Cars & Vehicles</option>
                    <option>Women's Fashion</option>
                    <option>Men's Accessories</option>
                    <option>Fitness Equipment</option>
                    <option>Perfumes & Fragrances</option>
                </select>
                <button id="searchButton" class="bg-gray-700 text-white px-4 flex items-center justify-center rounded-[4px]">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- signup/login -->
        <!-- <div class="text-right flex bg-gray-400 text-white rounded-[5px]"> -->
        <?php
            if (!isset($_SESSION['user_id'])) {
                echo <<<HTML
                <div class="text-right flex  bg-gray-400 text-white rounded-[5px]">
                    <div class="position-relative group">
                        <button class="text-white hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center">
                            Sign Up &nbsp; <i class="fa fa-caret-down"> </i>
                        </button>
                        <div class="absolute w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block" style="z-index: 100;">
                            <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600" onclick="userType('customer')">
                                Customer Sign Up
                            </a>
                            <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600" onclick="userType('vendor')">
                                Vendor Sign Up
                            </a>
                        </div>
                    </div>
                    <div class="text-gray-500"> | </div>
                    <div class="position-relative group">
                        <button class="text-white hover:text-orange-600 px-3 py-2 text-sm font-medium flex items-center">
                            Login &nbsp; <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="absolute w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block" style="z-index: 100;">
                            <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Customer Login</a>
                            <a href="#" class="login-btn text-left block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Vendor Login</a>
                        </div>
                    </div>
                </div>
                HTML;
            } 
            else {
                $dashboardPage = isset($_SESSION['type']) && $_SESSION['type'] == 'vendor' 
                            ? './vendor/dashboard.php' 
                            : './customer/dashboard.php';
                $sql = isset($_SESSION['type']) && $_SESSION['type'] == 'vendor'
                            ? "SELECT profile_picture AS img FROM vendor_basic_info WHERE user_id = ?"
                            : "SELECT profile_image AS img FROM customer_profile WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $result = $result->fetch_assoc();
                $img = $result['img'];
                $imgPath = $_SESSION['type'] == 'vendor' ? "vendor/": "customer/";
                $imgPath .= $img;
                // if (isset($img)) {
                // }
                // else {
                    // $imgPath = "img/default_profile.webp";
                // }

                echo <<<HTML
                <div class="text-right relative">
                    <a href="$dashboardPage">
                        <img class="w-[50px] h-[50px] rounded-full" src="$imgPath" alt="">
                    </a>
                    <span class="absolute bottom-[-5px] right-[0px] bg-black px-1 rounded-full text-white"><i class="fas fa-bars"></i></span>
                </div>
                HTML;
            }
        ?>
    </div>
    <hr class="my-2">

    
    <!-- Search Results Section -->
    <section id="searchResults" class="bg-white p-4 m-auto mt-4 hidden">
        <h2 class="font-bold mb-4">Search Results</h2>
        <div class="grid grid-cols-5 gap-4" id="productsGrid"></div>
        <div class="flex justify-center mt-4">
            <button id="loadMore" class="bg-gray-700 text-white px-4 py-2 rounded hidden">Load More</button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categorySelect');
            const searchButton = document.getElementById('searchButton');
            const searchResults = document.getElementById('searchResults');
            const productsGrid = document.getElementById('productsGrid');
            const loadMoreButton = document.getElementById('loadMore');
            
            let currentPage = 1;
            let isLoading = false;
            let hasMore = false;

            // Function to fetch products
            function fetchProducts(page = 1, append = false) {
                if (isLoading) return;
                
                isLoading = true;
                if (!append) {
                    currentPage = 1;
                    productsGrid.innerHTML = '';
                }
                
                const searchTerm = searchInput.value.trim();
                const category = categorySelect.value;
                
                fetch(`search-in-front.php?search=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(category)}&page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }
                        
                        if (!append && data.products.length === 0) {
                            productsGrid.innerHTML = '<p class="col-span-5 text-center">No products found</p>';
                        } else {
                            displayProducts(data.products, append);
                        }
                        
                        hasMore = data.hasMore;
                        if (hasMore) {
                            loadMoreButton.classList.remove('hidden');
                        } else {
                            loadMoreButton.classList.add('hidden');
                        }
                        
                        searchResults.classList.remove('hidden');
                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        isLoading = false;
                    });
            }

            // Function to display products
            function displayProducts(products, append) {
                if (!append) {
                    productsGrid.innerHTML = '';
                }
                
                products.forEach(product => {
                    const timeAgo = formatTimeAgo(product.updated_at);
                    const discount = product.mrp > product.price ? 
                        `<div class="flex items-center gap-2">
                            <p class="text-gray-500 line-through text-sm">${product.mrp} PKR</p>
                            <p class="text-[#c50] font-bold">${product.price} PKR</p>
                        </div>
                        <p class="text-green-600 text-sm">Save ${product.mrp - product.price} PKR</p>` : 
                        `<p class="text-[#c50] font-bold">${product.price} PKR</p>`;
                    
                    const productElement = document.createElement('a');
                    productElement.href = `product-details.php?id=${product.id}`;
                    productElement.className = 'd-inline-block';
                    productElement.innerHTML = `
                        <img src="./vendor/${product.image}" width="100%" height="100%" alt="${product.name}" class="h-40 object-cover">
                        <p class="my-1 h-8 overflow-hidden leading-4 line-clamp-2 text-sm">${product.name}</p>
                        ${discount}
                        <p class="my-1 text-xs text-gray-500">Updated ${timeAgo}</p>
                    `;
                    
                    productsGrid.appendChild(productElement);
                });
            }

            // Format time ago
            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
                return `${Math.floor(diffInSeconds / 86400)} days ago`;
            }

            // Event listeners
            searchButton.addEventListener('click', () => fetchProducts());
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') fetchProducts();
            });
            
            loadMoreButton.addEventListener('click', () => {
                currentPage++;
                fetchProducts(currentPage, true);
            });
        });
    </script>

    <div class="blur-bg-overlay"></div>
    <div class="form-popup" style="z-index: 1010;">
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
                            <a href="./term&condition.html" class="option">Terms & Conditions</a>
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
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column - Images -->
            <div class="md:w-[65%]">
                <div class="bg-white p-4 rounded-lg shadow mb-4 relative">
                    <!-- Main Image Container -->
                    <div class="flex justify-center items-center h-80 bg-gray-50 mb-4 relative">
                        <?php if (!empty($images)): ?>
                            <img src="vendor/<?php echo htmlspecialchars($images[0]['image_path']); ?>" 
                                alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                class="h-full object-contain" id="mainImage">
                        <?php else: ?>
                            <img src="../img/default-product.jpg" alt="No image" class="h-full object-contain" id="mainImage">
                        <?php endif; ?>

                        <!-- Left Arrow -->
                        <button onclick="showPrevImage()" 
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-2 py-1 rounded-full">
                            &#10094;
                        </button>

                        <!-- Right Arrow -->
                        <button onclick="showNextImage()" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-2 py-1 rounded-full">
                            &#10095;
                        </button>
                    </div>

                    <!-- Thumbnails -->
                    <div class="grid grid-cols-4 gap-2">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="h-20 bg-gray-50 border border-gray-200 cursor-pointer" 
                                onclick="setImage(<?php echo $index; ?>)">
                                <img src="vendor/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                    alt="Thumbnail <?php echo $index + 1; ?>" 
                                    class="h-full w-full object-contain">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <script>
                let images = <?php echo json_encode(array_column($images, 'image_path')); ?>;
                let currentIndex = 0;

                function setImage(index) {
                    currentIndex = index;
                    document.getElementById('mainImage').src = 'vendor/' + images[currentIndex];
                }

                function showPrevImage() {
                    if (images.length === 0) return;
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    setImage(currentIndex);
                }

                function showNextImage() {
                    if (images.length === 0) return;
                    currentIndex = (currentIndex + 1) % images.length;
                    setImage(currentIndex);
                }
            </script>


            <!-- Right Column - Details -->
            <div class="md:w-[50%]">
                <!-- Auction Info -->
                <div class="bg-white p-4 rounded-lg shadow mb-4 relative">
                    <div class="bg-gray-100 px-3 py-2 rounded-[10px] absolute right-5"> <i class="fa-heart far" id="favoriteBtn" style="cursor:pointer;"></i> </div>
                    <script>
                        // Get product ID from URL
                        const urlParams = new URLSearchParams(window.location.search);
                        const productId = urlParams.get('id');

                        document.getElementById('favoriteBtn').addEventListener('click', function () {
                            fetch('favorite.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: `product_id=${productId}`
                            })
                            .then(res => res.text())
                            .then(response => {
                                if (response === 'added') {
                                    this.classList.remove('far');
                                    this.classList.add('fas', 'text-red-500');
                                } else if (response === 'removed') {
                                    this.classList.remove('fas', 'text-red-500');
                                    this.classList.add('far');
                                } else {
                                    alert('Error: ' + response);
                                }
                            });
                        });
                    </script>


                    <h1 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <div class="mb">
                        <p class="text-sm text-gray-600">Current Price</p>                        
                        <?php if ($product['selling_price'] > $product['mrp']): ?>
                            <p class="price-text flex items-center justify-between">
                                <span class="text-gray-900">
                                    <small class="text-gray-600">PKR</small> <?php echo number_format($product['mrp'], 2); ?>
                                </span>
                                <span class="discount-badge bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1 rounded-md text-sm font-bold shadow-sm">
                                    SAVE <?php echo (int)(($product['selling_price'] - $product['mrp']) / $product['selling_price'] * 100); ?>%
                                </span>
                            </p>
                          <p class="text-sm text-gray-600"> <span class="font-medium"><del><?php echo number_format($product['selling_price'], 2); ?></del></span></p>
                        <?php else: ?>
                            <p class="price-text"><small>PKR</small> <?php echo number_format($product['selling_price'], 2); ?></p>
                        <?php endif; ?>
                      </div>

                    <!-- Rating and Author -->
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            <?php
                            $rating = $product['rating'] ?? 0;
                            $full_stars = floor($rating);
                            $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                            $empty_stars = 5 - $full_stars - $half_star;
                            
                            echo str_repeat('<i class="fas fa-star"></i>', $full_stars);
                            echo $half_star ? '<i class="fas fa-star-half-alt"></i>' : '';
                            echo str_repeat('<i class="far fa-star"></i>', $empty_stars);
                            ?>
                        </div>
                        <span class="ml-2 text-gray-700"><?php echo number_format($rating, 1); ?>/5</span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-gray-500"><?php echo date('M d, Y', strtotime($product['updated_at'])); ?></span>
                    </div>

                    <!-- price detail  -->
                    <div class="mb-4">
                        <h2 class="text-md font-bold">Price Detail</h2>
                        <ul class="text-sm text-gray-600" style="list-style-type: disc; margin-left: 1rem;">
                            <li><p class="mb-1">Product Price: <span class="font-medium">PKR <?php echo number_format($product['selling_price']-$product['delivery_charges'], 2); ?></span></p></li>
                            <li><p class="mb-1">Delivery Charges: <span class="font-medium">PKR <?php echo number_format($product['delivery_charges'], 2); ?></span></p></li>
                            <li>Expenses will be charged separately from the winning bid amount.</li>
                            <li>Free accessories included with purchase. </li>
                            <li>Offer valid while supplies last.</li>
                        </ul>
                    </div>

                    <div class="grid grid-cols-1 gap-3 mb-4">
                        <button class="buynow-button text-white font-bold py-3 px-4 rounded-lg">
                            <a href="./buy.php?productId=<?php echo $product_id; ?>">Buy It Now</a>
                        </button>
                        <!-- <button class="addcart-button text-white font-bold py-3 px-4 rounded-lg">
                            Add to cart
                        </button> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="v-stats grid grid-cols-1  md:grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-4 text-center mb-6">
            <?php foreach ($cards as $card): ?>
                <div class="bg-white p-4 rounded shadow">
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($card['card_title']); ?></p>
                    <p class="text-lg text-black"><?php echo htmlspecialchars($card['card_value']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column -->
            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow mt6 w-full md:w-[65%]">
                <div class="flex border-b border-gray-200">
                    <button class="py-3 px-6 tab-button active" data-tab="description">Description</button>
                    <button class="py-3 px-6 tab-button" data-tab="specs">Specifications</button>
                    <button class="py-3 px-6 tab-button" data-tab="faults">Faults</button>
                    <button class="py-3 px-6 tab-button" data-tab="shipping">Shipping & Payment</button>
                </div>

                <!-- Description Tab -->
                <div id="description" class="tab-content active p-6">
                    <h2 class="font-bold text-lg mb-4">Product Description</h2>
                    <div class="description-text text-sm mb-6">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-bold mb-2">Important Notes</h3>
                        <ul class="text-sm list-disc pl-5 space-y-1">
                            <li>Please contact us within 24 hours after winning the bid</li>
                            <li>Bid cancellations are not accepted</li>
                            <li>For privacy protection, the product name will not be listed on the shipping label</li>
                            <li>We may reject bids from users with poor feedback ratings</li>
                        </ul>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specs" class="tab-content p-6 hidden">
                    <h2 class="font-bold text-lg mb-4">Product Specifications</h2>
                    <table class="w-full text-sm">
                        <?php foreach ($cards as $card): ?>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 text-gray-600 w-1/3"><?php echo htmlspecialchars($card['card_title']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($card['card_value']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Faults Tab -->
                <div id="faults" class="tab-content p-6 hidden">
                    <h2 class="font-bold text-lg mb-4">Product Faults</h2>
                    <?php if (!empty($faults)): ?>
                        <div class="space-y-4">
                            <?php foreach ($faults as $fault): ?>
                                <div class="border-b border-gray-200 pb-4">
                                    <?php if (!empty($fault['fault_image'])): ?>
                                        <img src="vendor/<?php echo htmlspecialchars($fault['fault_image']); ?>" alt="Fault image" class="h-40 object-contain mb-2">
                                    <?php endif; ?>
                                    <p class="text-sm"><?php echo nl2br(htmlspecialchars($fault['fault_description'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-600">This product has no reported faults.</p>
                    <?php endif; ?>
                </div>

                <!-- Shipping & Payment Tab -->
                <div id="shipping" class="tab-content p-6 hidden">
                    <h2 class="font-bold text-lg mb-4">Shipping & Payment Information</h2>
                    <table class="w-full text-sm">
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600 w-1/3">Shipping Cost</td>
                            <td class="py-2">PKR <?php echo number_format($product['delivery_charges'], 2); ?></td>
                        </tr>
                        <!-- <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600">Shipping Method</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['shipping_method']); ?></td>
                        </tr> -->
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600">Ships From</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['location']); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Shipping Time</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['shipping_time']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="md:w-[35%]">
                <!-- Product Specs -->
                <div class="bg-white p-4 rounded-lg shadow mb-4">
                    <h3 class="font-bold text-lg mb-3">Product Information</h3>
                    <table class="w-full text-sm">
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600 w-1/3">Category</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['category']); ?></td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600">Condition</td>
                            <td class="py-2"><?php echo htmlspecialchars(ucfirst($product['condition'])); ?></td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600">Shipping Cost</td>
                            <td class="py-2">PKR <?php echo number_format($product['delivery_charges'], 2); ?></td>
                        </tr>
                        <!-- <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600">Shipping Method</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['shipping_method']); ?></td>
                        </tr> -->
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-gray-600">Ships From</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['location']); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Shipping Time</td>
                            <td class="py-2"><?php echo htmlspecialchars($product['shipping_time']); ?></td>
                        </tr>
                    </table>
                </div>
                <!-- review  -->
                <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Review Header -->
                    <div class="p-3">
                        <div class="grid grid-cols-[1fr_3fr] gap-2">
                            <img src="vendor/uploads/<?php echo !empty($product['seller_image']) ? htmlspecialchars($product['seller_image']) : '../img/default_profile.webp'; ?>" 
                                 alt="<?php echo htmlspecialchars($product['seller_name']); ?>" 
                                 style="height: 80px !important; width: 80px !important; object-fit: cover;">
                            <div>
                                <h2 class="text-md font-bold text-gray-800"><?php echo htmlspecialchars($product['seller_name']); ?></h2>
                                <p class="text-sm text-gray-600 mt-1">Seller</p>
                            </div>
                        </div>

                        <!-- Rating and Author -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400">
                                <?php
                                $rating = $product['rating'] ?? 0;
                                $full_stars = floor($rating);
                                $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                                $empty_stars = 5 - $full_stars - $half_star;
                                
                                echo str_repeat('<i class="fas fa-star"></i>', $full_stars);
                                echo $half_star ? '<i class="fas fa-star-half-alt"></i>' : '';
                                echo str_repeat('<i class="far fa-star"></i>', $empty_stars);
                                ?>
                            </div>
                            <span class="ml-2 text-gray-700"><?php echo number_format($rating, 1); ?>/5</span>
                            <span class="mx-2 text-gray-400">•</span>
                            <span class="text-gray-500"><?php echo date('M d, Y', strtotime($product['updated_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Column 1 -->
                <div>
                    <h3 class="text-lg font-bold mb-4">About PakWheels</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-orange-400">About Us</a></li>
                        <li><a href="#" class="hover:text-orange-400">Careers</a></li>
                        <li><a href="#" class="hover:text-orange-400">Contact Us</a></li>
                        <li><a href="#" class="hover:text-orange-400">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Column 2 -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Buy & Sell</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-orange-400">Used Cars</a></li>
                        <li><a href="#" class="hover:text-orange-400">New Cars</a></li>
                        <li><a href="#" class="hover:text-orange-400">Bikes</a></li>
                        <li><a href="#" class="hover:text-orange-400">Auto Parts</a></li>
                    </ul>
                </div>

                <!-- Column 3 -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-orange-400">Car Inspection</a></li>
                        <li><a href="#" class="hover:text-orange-400">Sell It For Me</a></li>
                        <li><a href="#" class="hover:text-orange-400">Auction Sheet Verification</a></li>
                        <li><a href="#" class="hover:text-orange-400">Car Financing</a></li>
                    </ul>
                </div>

                <!-- Column 4 -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4 mb-4">
                        <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-youtube"></i></a>
                    </div>
                    <p class="text-sm text-gray-400">Download our mobile app:</p>
                    <div class="flex space-x-2 mt-2">
                        <a href="#"><img src="https://www.pakwheels.com/assets/google-play-badge-6c1f7d2f.png"
                                alt="Google Play" class="h-10"></a>
                        <a href="#"><img src="https://www.pakwheels.com/assets/app-store-badge-0b4b1b0e.png"
                                alt="App Store" class="h-10"></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2023 PakWheels (Pvt) Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
    
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and hide all content
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                    const tabId = btn.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('hidden');
                });
                
                // Add active class to clicked button and show corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Mobile menu toggle
        document.getElementById('nav-btn').addEventListener('click', function() {
            document.getElementById('nav-mob').classList.toggle('hidden');
        });
    </script>
</body>

</html>