<?php
session_start();
@include '../mydatabase/conn.php';
$user_id = $_SESSION['user_id'];
// echo $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK;

// Initialize variables to store existing data
$basic_info = [];
$store_details = [];
$address_info = [];

// Check if form is submitted (Finish tab)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process all form data at once when submitted from Finish tab
    
    // Basic Info
    $full_name = $_POST['full_name'] ?? '';
    $store_name = $_POST['store_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $profile_visibility = isset($_POST['profile_visibility']) ? 1 : 0;
    
    // Handle profile picture upload
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './uploads/';
        $file_name = "profiles_" . uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
            $profile_picture = $target_path;
        }
    }
    echo $profile_picture;
    
    // Store Details
    $business_type = $_POST['business_type'] ?? '';
    $store_category = $_POST['store_category'] ?? '';
    $store_description = $_POST['store_description'] ?? '';
    $return_policy = $_POST['return_policy'] ?? '';
    $shipping_policy = $_POST['shipping_policy'] ?? '';
    $store_video = $_POST['store_video'] ?? '';
    
    // Handle store logo upload
    $store_logo = '';
    if (isset($_FILES['store_logo']) && $_FILES['store_logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './uploads/';
        $file_name = "store_logos_" . uniqid() . '_' . basename($_FILES['store_logo']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['store_logo']['tmp_name'], $target_path)) {
            $store_logo = $target_path;
        }
    }
    
    // Handle store banner upload
    $store_banner = '';
    if (isset($_FILES['store_banner']) && $_FILES['store_banner']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './uploads/';
        $file_name = "store_banners_" . uniqid() . '_' . basename($_FILES['store_banner']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['store_banner']['tmp_name'], $target_path)) {
            $store_banner = $target_path;
        }
    }
    
    // Handle policy file uploads
    $return_policy_file = '';
    if (isset($_FILES['return_policy_file']) && $_FILES['return_policy_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './uploads/';
        $file_name = "policies_" . uniqid() . '_' . basename($_FILES['return_policy_file']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['return_policy_file']['tmp_name'], $target_path)) {
            $return_policy_file = $target_path;
        }
    }
    
    $shipping_policy_file = '';
    if (isset($_FILES['shipping_policy_file']) && $_FILES['shipping_policy_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './uploads/';
        $file_name = "policies" . uniqid() . '_' . basename($_FILES['shipping_policy_file']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['shipping_policy_file']['tmp_name'], $target_path)) {
            $shipping_policy_file = $target_path;
        }
    }
    
    // Address Info
    $pickup_address = $_POST['pickup_address'] ?? '';
    $city = $_POST['city'] ?? '';
    $area = $_POST['area'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    
    // Insert or update basic info
    $stmt = $conn->prepare("INSERT INTO vendor_basic_info 
        (user_id, profile_picture, full_name, store_name, phone, profile_visibility) 
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        profile_picture = IFNULL(VALUES(profile_picture), profile_picture),
        full_name = VALUES(full_name),
        store_name = VALUES(store_name),
        phone = VALUES(phone),
        profile_visibility = VALUES(profile_visibility)");
    $stmt->bind_param("issssi", $user_id, $profile_picture, $full_name, $store_name, $phone, $profile_visibility);
    $stmt->execute();
    
    // Insert or update store details
    $stmt = $conn->prepare("INSERT INTO vendor_store_details 
        (user_id, business_type, store_category, store_description, store_logo, store_banner, 
        return_policy, return_policy_file, shipping_policy, shipping_policy_file, store_video) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        business_type = VALUES(business_type),
        store_category = VALUES(store_category),
        store_description = VALUES(store_description),
        store_logo = IFNULL(VALUES(store_logo), store_logo),
        store_banner = IFNULL(VALUES(store_banner), store_banner),
        return_policy = VALUES(return_policy),
        return_policy_file = IFNULL(VALUES(return_policy_file), return_policy_file),
        shipping_policy = VALUES(shipping_policy),
        shipping_policy_file = IFNULL(VALUES(shipping_policy_file), shipping_policy_file),
        store_video = VALUES(store_video)");
    $stmt->bind_param("issssssssss", $user_id, $business_type, $store_category, $store_description, 
        $store_logo, $store_banner, $return_policy, $return_policy_file, $shipping_policy, 
        $shipping_policy_file, $store_video);
    $stmt->execute();
    
    // Insert or update address
    $stmt = $conn->prepare("INSERT INTO vendor_address 
        (user_id, pickup_address, city, area, postal_code) 
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        pickup_address = VALUES(pickup_address),
        city = VALUES(city),
        area = VALUES(area),
        postal_code = VALUES(postal_code)");
    $stmt->bind_param("issss", $user_id, $pickup_address, $city, $area, $postal_code);
    $stmt->execute();
    
    // Redirect to profile page
    header("Location: ./profile.php");
    exit();
}
?>