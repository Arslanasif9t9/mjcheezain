<?php
session_start();
require_once '../mydatabase/conn.php'; // Database connection

if (!isset($_SESSION['user_id']) || !isset($_SESSION['type']) || $_SESSION['type'] !== "vendor") {
    header("Location: ../index.php");
    exit();
}

// Function to handle file uploads
function uploadFile($file, $productId, $isFault = false) {
    $uploadDir = $isFault ? './uploads/faults/' : './uploads/products/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'product_' . $productId . '_' . uniqid() . '.' . $fileExt;
    $filePath = $uploadDir . $fileName;
    
    // Check if file is an image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return $filePath;
    }
    
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction (MySQLi way)
    $conn->autocommit(FALSE); // Turn off auto-commit
    $error = false;

    try {
        // Insert product details
        // Check if MRP is provided, and validate only if it exists
        if (isset($_POST['mrp']) && !empty($_POST['mrp'])) {
            if ($_POST['mrp'] <= 0) {
                $mrp = NULL;
            }
            $mrp = $_POST['mrp'];
        } else {
            $mrp = NULL; // MRP is optional, set to NULL if not provided
        }
        $stmt = $conn->prepare("
            INSERT INTO vendor_products (
                user_id, name, category, subcategory, quantity, brand, model, 
                `condition`, original_price, delivery_charges, selling_price, mrp,
                shipping_method, shipping_time, description, location, position
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $position = "pending";
        $stmt->bind_param("issiissssiiisssss",
            $_SESSION['user_id'],
            $_POST['product_name'],
            $_POST['category'],
            $_POST['subcategory'],  // Fixed order - this was after quantity in original
            $_POST['quantity'],
            $_POST['brand'],
            $_POST['model'],
            $_POST['condition'],
            $_POST['original_price'],
            $_POST['delivery_charges'],
            $_POST['selling_price'],
            $mrp,  // Added MRP parameter
            $_POST['shipping_method'],
            $_POST['shipping_time'],
            $_POST['description'],
            $_POST['location'],
            $position
        );

        
        if (!$stmt->execute()) {
            throw new Exception("Product insert failed: " . $stmt->error);
        }
        
        $productId = $conn->insert_id;
        
        // Handle product images
        if (!empty($_FILES['productImages'])) {
            $primarySet = false;
            
            foreach ($_FILES['productImages']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['productImages']['error'][$key] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['productImages']['name'][$key],
                        'type' => $_FILES['productImages']['type'][$key],
                        'tmp_name' => $tmpName,
                        'error' => $_FILES['productImages']['error'][$key],
                        'size' => $_FILES['productImages']['size'][$key]
                    ];
                    
                    $filePath = uploadFile($file, $productId);
                    
                    if ($filePath) {
                        $isPrimary = !$primarySet;
                        if ($isPrimary) $primarySet = true;
                        
                        $imgStmt = $conn->prepare("
                            INSERT INTO vendor_product_images 
                            (product_id, image_path, is_primary) VALUES (?, ?, ?)
                        ");
                        $imgStmt->bind_param("isi", $productId, $filePath, $isPrimary);
                        
                        if (!$imgStmt->execute()) {
                            throw new Exception("Image insert failed: " . $imgStmt->error);
                        }
                    }
                }
            }
        }
        
        // Handle product cards
        if (!empty($_POST['cards'])) {
            foreach ($_POST['cards'] as $card) {
                if (!empty($card['title']) && !empty($card['value'])) {
                    $cardStmt = $conn->prepare("
                        INSERT INTO vendor_product_cards 
                        (product_id, card_title, card_value) VALUES (?, ?, ?)
                    ");
                    $cardStmt->bind_param("iss", $productId, $card['title'], $card['value']);
                    
                    if (!$cardStmt->execute()) {
                        throw new Exception("Card insert failed: " . $cardStmt->error);
                    }
                }
            }
        }
        
        // Handle product faults
        if (!empty($_FILES['faults'])) {
            foreach ($_FILES['faults']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['faults']['error'][$key] === UPLOAD_ERR_OK && 
                    !empty($_POST['fault_descriptions'][$key])) {
                    
                    $file = [
                        'name' => $_FILES['faults']['name'][$key],
                        'type' => $_FILES['faults']['type'][$key],
                        'tmp_name' => $tmpName,
                        'error' => $_FILES['faults']['error'][$key],
                        'size' => $_FILES['faults']['size'][$key]
                    ];
                    
                    $filePath = uploadFile($file, $productId, true);
                    
                    $faultStmt = $conn->prepare("
                        INSERT INTO vendor_product_faults 
                        (product_id, fault_image, fault_description) VALUES (?, ?, ?)
                    ");
                    $faultStmt->bind_param("iss", $productId, $filePath, $_POST['fault_descriptions'][$key]);
                    
                    if (!$faultStmt->execute()) {
                        throw new Exception("Fault insert failed: " . $faultStmt->error);
                    }
                }
            }
        }
        
        // Commit transaction if everything succeeded
        $conn->commit();
        header("Location: products.php?success=1");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "Product submission error: " . $e->getMessage();
        // error_log("Product submission error: " . $e->getMessage());
        // header("Location: new_product.php?error=1");
        exit();
    } finally {
        $conn->autocommit(TRUE); // Turn auto-commit back on
    }
} else {
    // header("Location: new_product.php");
    exit();
}