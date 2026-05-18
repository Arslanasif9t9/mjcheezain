<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
} elseif (isset($_SESSION['type']) && $_SESSION['type'] == "customer") {
    header("Location: ../customer/dashboard.php");
    exit();
}

require_once '../mydatabase/conn.php';

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID";
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // Begin transaction
    $conn->autocommit(FALSE);

    // First, check if the product belongs to the current user
    $check_stmt = $conn->prepare("SELECT id FROM vendor_products WHERE id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $product_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Product not found or you don't have permission to delete it");
    }

    // Get all image paths associated with this product
    $image_stmt = $conn->prepare("SELECT image_path FROM vendor_product_images WHERE product_id = ?");
    $image_stmt->bind_param("i", $product_id);
    $image_stmt->execute();
    $images_result = $image_stmt->get_result();
    $image_paths = [];
    while ($row = $images_result->fetch_assoc()) {
        $image_paths[] = $row['image_path'];
    }

    // Delete from vendor_product_faults first (child table)
    $fault_stmt = $conn->prepare("DELETE FROM vendor_product_faults WHERE product_id = ?");
    $fault_stmt->bind_param("i", $product_id);
    $fault_stmt->execute();

    // Delete from vendor_product_cards (child table)
    $card_stmt = $conn->prepare("DELETE FROM vendor_product_cards WHERE product_id = ?");
    $card_stmt->bind_param("i", $product_id);
    $card_stmt->execute();

    // Delete from vendor_product_images (child table)
    $image_delete_stmt = $conn->prepare("DELETE FROM vendor_product_images WHERE product_id = ?");
    $image_delete_stmt->bind_param("i", $product_id);
    $image_delete_stmt->execute();

    // Finally, delete the product itself
    $delete_stmt = $conn->prepare("DELETE FROM vendor_products WHERE id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $product_id, $user_id);
    $delete_stmt->execute();

    if ($delete_stmt->affected_rows === 0) {
        throw new Exception("Failed to delete product");
    }

    // Delete the associated image files if they exist
    foreach ($image_paths as $image_path) {
        if (!empty($image_path) && file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Also delete any fault images
    $fault_image_stmt = $conn->prepare("SELECT fault_image FROM vendor_product_faults WHERE product_id = ? AND fault_image IS NOT NULL");
    $fault_image_stmt->bind_param("i", $product_id);
    $fault_image_stmt->execute();
    $fault_images_result = $fault_image_stmt->get_result();
    while ($row = $fault_images_result->fetch_assoc()) {
        if (!empty($row['fault_image']) && file_exists($row['fault_image'])) {
            unlink($row['fault_image']);
        }
    }

    // Commit transaction
    $conn->commit();
    $_SESSION['success'] = "Product and all associated data deleted successfully";
    header("Location: products.php");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error'] = $e->getMessage();
    header("Location: products.php");
    exit();
} finally {
    // Close statements if they exist
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($delete_stmt)) $delete_stmt->close();
    if (isset($fault_stmt)) $fault_stmt->close();
    if (isset($card_stmt)) $card_stmt->close();
    if (isset($image_stmt)) $image_stmt->close();
    if (isset($image_delete_stmt)) $image_delete_stmt->close();
    if (isset($fault_image_stmt)) $fault_image_stmt->close();
    
    // Turn autocommit back on
    $conn->autocommit(TRUE);
}