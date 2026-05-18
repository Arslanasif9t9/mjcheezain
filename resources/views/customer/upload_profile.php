<?php
@include "./redirect_vendor.php";
@include "../mydatabase/conn.php";
// Get form data
$user_id = $_GET['user_id'];
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$birthday = $_POST['birthday'];
$bio = $_POST['bio'];

// Initialize profileImage as null (not empty string)
$profileImage = null;

// Handle profile image upload
if (isset($_FILES['profile-upload']) && $_FILES['profile-upload']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/";
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Generate unique filename to prevent overwrites
    $fileExtension = pathinfo($_FILES["profile-upload"]["name"], PATHINFO_EXTENSION);
    $newFilename = uniqid() . '.' . $fileExtension;
    $profileImage = $targetDir . $newFilename;
    
    if (move_uploaded_file($_FILES["profile-upload"]["tmp_name"], $profileImage)) {
        // File upload successful
    } else {
        // Handle upload error
        $profileImage = null;
    }
}

// Build SQL dynamically
$sql = "INSERT INTO customer_profile (user_id, first_name, last_name, email, phone, birthday, bio" . 
       ($profileImage !== null ? ", profile_image" : "") . 
       ") VALUES (?, ?, ?, ?, ?, ?, ?" . 
       ($profileImage !== null ? ", ?" : "") . 
       ") ON DUPLICATE KEY UPDATE 
        first_name = VALUES(first_name),
        last_name = VALUES(last_name),
        email = VALUES(email),
        phone = VALUES(phone),
        birthday = VALUES(birthday),
        bio = VALUES(bio)" .
       ($profileImage !== null ? ", profile_image = VALUES(profile_image)" : "");

$stmt = $conn->prepare($sql);

if ($profileImage !== null) {
    $stmt->bind_param("isssssss", $user_id, $firstName, $lastName, $email, $phone, $birthday, $bio, $profileImage);
} else {
    $stmt->bind_param("issssss", $user_id, $firstName, $lastName, $email, $phone, $birthday, $bio);
}

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Profile " . ($stmt->affected_rows == 1 ? "saved" : "updated") . " successfully.";
        header('location: profile.php');
    } else {
        header('location: profile.php');
        echo "No changes made to the profile.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
