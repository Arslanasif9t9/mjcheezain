<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cheezaindb";

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
// $conn->query($sql) or die("Error creating database");
if ($conn->query($sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}
$conn->select_db($dbname);


// Create users table (prerequisite for vendor tables)
$sql = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    type VARCHAR(20) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if ($conn->query($sql)) {
    echo "users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}


// Create vendor_basic_info table
$sql = "CREATE TABLE IF NOT EXISTS vendor_basic_info (
    user_id INT PRIMARY KEY,
    profile_picture VARCHAR(255) DEFAULT 'uploads/default_profile.webp',
    full_name VARCHAR(100) NOT NULL,
    store_name VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20) NOT NULL,
    profile_visibility BOOLEAN DEFAULT TRUE,
    rating DECIMAL(3,2) CHECK (rating >= 0.00 AND rating <= 5.00),
    varified BOOLEAN DEFAULT FALSE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "vendor_basic_info table created successfully<br>";
} else {
    echo "Error creating vendor_basic_info table: " . $conn->error . "<br>";
}


// Create vendor_store_details table
$sql = "CREATE TABLE IF NOT EXISTS vendor_store_details (
    user_id INT PRIMARY KEY,
    business_type ENUM('individual', 'company') NOT NULL,
    store_category ENUM('electronics', 'fashion', 'home', 'beauty', 'food') NOT NULL,
    store_description TEXT,
    store_logo VARCHAR(255),
    store_banner VARCHAR(255),
    return_policy TEXT,
    return_policy_file VARCHAR(255),
    shipping_policy TEXT,
    shipping_policy_file VARCHAR(255),
    store_video VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "vendor_store_details table created successfully<br>";
} else {
    echo "Error creating vendor_store_details table: " . $conn->error . "<br>";
}


// Create vendor_address table
$sql = "CREATE TABLE IF NOT EXISTS vendor_address (
    user_id INT PRIMARY KEY,
    pickup_address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    area VARCHAR(50) NOT NULL,
    country VARCHAR(50) DEFAULT 'Pakistan',
    postal_code VARCHAR(20),
    map_location VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "vendor_address table created successfully<br>";
} else {
    echo "Error creating vendor_address table: " . $conn->error . "<br>";
}


// Create vendor_products table
$sql = "CREATE TABLE IF NOT EXISTS vendor_products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(255),
    category VARCHAR(100),
    quantity INT NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    `condition` VARCHAR(100) NOT NULL,  -- Escaped with backticks
    original_price INT NOT NULL,
    delivery_charges INT NOT NULL,
    selling_price INT NOT NULL,
    shipping_method VARCHAR(50) NOT NULL,
    shipping_time VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    status INT,
    rating DECIMAL(3,2) CHECK (rating >= 0.00 AND rating <= 5.00),
    position ENUM('pending', 'approved', 'rejected', 'disabled') DEFAULT 'pending',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql)) {
    echo "vendor_products table created successfully<br>";
} else {
    echo "Error creating vendor_products table: " . $conn->error . "<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS vendor_product_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (product_id) REFERENCES vendor_products(id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "vendor_product_images table created successfully<br>";
} else {
    echo "Error creating vendor_product_images table: " . $conn->error . "<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS vendor_product_cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    card_title VARCHAR(100) NOT NULL,
    card_value VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES vendor_products(id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "vendor_product_cards table created successfully<br>";
} else {
    echo "Error creating vendor_product_cards table: " . $conn->error . "<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS vendor_product_faults (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    fault_image VARCHAR(255),
    fault_description TEXT,
    FOREIGN KEY (product_id) REFERENCES vendor_products(id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "vendor_product_faults table created successfully<br>";
} else {
    echo "Error creating vendor_product_faults table: " . $conn->error . "<br>";
}














// Create customer_profile table
$sql = "CREATE TABLE IF NOT EXISTS customer_profile (
    user_id INT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(20),
    birthday DATE,
    bio TEXT,
    profile_image VARCHAR(255) DEFAULT 'uploads/default_profile.webp',  -- Save image filename/path
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";
if ($conn->query($sql)) {
    echo "customer_profile table created successfully<br>";
} else {
    echo "Error creating customer_profile table: " . $conn->error . "<br>";
}



$sql = "CREATE TABLE IF NOT EXISTS customer_recent_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type ENUM('order_placed', 'order_delivered', 'wishlist', 'review') NOT NULL,
    title VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    points VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);";
if ($conn->query($sql)) {
    echo "customer_recent_activity table created successfully<br>";
} else {
    echo "Error creating customer_recent_activity table: " . $conn->error . "<br>";
}



$sql = "CREATE TABLE IF NOT EXISTS customer_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_type VARCHAR(50) NOT NULL COMMENT 'Home, Work, etc.',
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql)) {
    echo "customer_addresses table created successfully<br>";
} else {
    echo "Error creating customer_addresses table: " . $conn->error . "<br>";
}



// SQL to create orders table
$sqlOrders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    vendor_id INT NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_charges DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fulfillment ENUM('pending', 'unfulfillment', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    vendor_visible BOOLEAN DEFAULT FALSE,
    payment_method ENUM('bank_transfer', 'cash_on_delivery', 'credit_card') NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES vendor_products(id) ON DELETE CASCADE,
    FOREIGN KEY (vendor_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (shipping_address_id) REFERENCES customer_addresses(id) ON DELETE CASCADE
)";
// Execute orders table creation
if ($conn->query($sqlOrders)) {
    echo "Orders table created successfully or already exists<br>";
} 
else {
    echo "Error creating orders table: " . $conn->error . "<br>";
}



// SQL to create payments table
$sqlPayments = "CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    bank_name VARCHAR(100) NOT NULL,
    account_holder VARCHAR(100) NOT NULL,
    transaction_ref VARCHAR(100) NOT NULL,
    transaction_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    receipt_path VARCHAR(255) NOT NULL,
    payment_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
)";

// Execute payments table creation
if ($conn->query($sqlPayments)) {
    echo "Payments table created successfully or already exists.<br>";
} else {
    echo "Error creating payments table: " . $conn->error . "<br>";
}

$conn->query("ALTER TABLE users ADD COLUMN status ENUM('active', 'blocked', 'pending') DEFAULT 'pending';");
$conn->query("ALTER TABLE users ADD COLUMN flagged BOOLEAN DEFAULT FALSE;");
$conn->query("ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL;");
$conn->query("CREATE TABLE IF NOT EXISTS customer_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    admin_id INT NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);");


$conn->query("ALTER TABLE users ADD COLUMN verified BOOLEAN DEFAULT FALSE;");
$conn->query("CREATE TABLE IF NOT EXISTS vendor_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    document_type ENUM('id_proof', 'address_proof', 'tax_document', 'other') NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);");
$conn->query("CREATE TABLE IF NOT EXISTS vendor_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_reference VARCHAR(100),
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);");


$conn->query("ALTER TABLE vendor_products ADD COLUMN views INT DEFAULT 0;");
$conn->query("ALTER TABLE vendor_products ADD COLUMN admin_notes TEXT;");
$conn->query("ALTER TABLE vendor_products ADD COLUMN approved_by INT;");
$conn->query("ALTER TABLE vendor_products ADD COLUMN approved_at TIMESTAMP NULL;");
$conn->query("CREATE TABLE vendor_balance (
    user_id INT PRIMARY KEY,
    total_balance DECIMAL(10,2) DEFAULT 0.00,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);");


$conn->query("CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fav (user_id, product_id),
    CONSTRAINT fk_fav_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_fav_product FOREIGN KEY (product_id) REFERENCES vendor_products(id) ON DELETE CASCADE
)");

// Add this to your database creation script
$conn->query("CREATE TABLE IF NOT EXISTS withdrawal_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    withdrawal_method ENUM('jazzcash', 'easypaisa', 'bank_transfer') NOT NULL,
    bank_name VARCHAR(100),
    account_number VARCHAR(100) NOT NULL,
    account_holder_name VARCHAR(100) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);");


// add subcategory colume 
$conn->query("ALTER TABLE vendor_products
ADD COLUMN subcategory VARCHAR(255) AFTER category;");




// Create users table
    $sql = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        reset_token VARCHAR(255) DEFAULT NULL,
        token_expires_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql)) {
        // Check if admin user exists
        $result = $conn->query("SELECT id FROM admin_users WHERE username = 'admin'");
        if ($result->num_rows == 0) {
            // Insert sample admin user (password: Admin@123)
            $password_hash = password_hash('Admin@123', PASSWORD_DEFAULT);
            $conn->query("INSERT INTO admin_users (username, email, password_hash) 
                         VALUES ('admin', 'admin@example.com', '$password_hash')");
        }
    } else {
        die("Error creating users table: " . $conn->error);
    }
    
    // Create password_reset_tokens table
    $sql = "CREATE TABLE IF NOT EXISTS admin_password_reset_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        used TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE
    )";
    
    if (!$conn->query($sql)) {
        die("Error creating password_reset_tokens table: " . $conn->error);
    }

$conn->query('ALTER TABLE `vendor_products`
ADD COLUMN mrp DECIMAL(10,2) AFTER selling_price;');

$conn->close();
?>

