<?php
@include "../mydatabase/conn.php";
$product_id = $_GET['id'];

// Get product details
$product_query = "SELECT p.*, u.username AS vendor_name, vbi.store_name 
                  FROM vendor_products p
                  JOIN users u ON p.user_id = u.user_id
                  JOIN vendor_basic_info vbi ON p.user_id = vbi.user_id
                  WHERE p.id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Get product images
$images_query = "SELECT * FROM vendor_product_images WHERE product_id = ?";
$stmt = $conn->prepare($images_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get product cards
$cards_query = "SELECT * FROM vendor_product_cards WHERE product_id = ?";
$stmt = $conn->prepare($cards_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$cards = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get product faults
$faults_query = "SELECT * FROM vendor_product_faults WHERE product_id = ?";
$stmt = $conn->prepare($faults_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$faults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Determine status
$status_class = '';
$status_text = '';
switch ($product['status']) {
    case 0:
        $status_class = 'status-pending';
        $status_text = 'Pending';
        break;
    case 1:
        $status_class = 'status-approved';
        $status_text = 'Approved';
        break;
    case 2:
        $status_class = 'status-rejected';
        $status_text = 'Rejected';
        break;
    case 3:
        $status_class = 'status-disabled';
        $status_text = 'Disabled';
        break;
    default:
        $status_class = 'status-pending';
        $status_text = 'Unknown';
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Basic Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['brand']); ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['category']); ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vendor</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['store_name']); ?>" readonly>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Pricing & Inventory</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Original Price (Rs.)</label>
                        <input type="text" class="form-control" value="<?php echo number_format($product['original_price']); ?>" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Delivery Charges (Rs.)</label>
                        <input type="text" class="form-control" value="<?php echo number_format($product['delivery_charges']); ?>" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Selling Price (Rs.)</label>
                        <input type="text" class="form-control" value="<?php echo number_format($product['selling_price']); ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" value="<?php echo $product['quantity']; ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Condition</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['condition']); ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shipping Method</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['shipping_method']); ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shipping Time</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['shipping_time']); ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Product Images</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="gallery-item">
                                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" class="img-fluid">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <?php if (!empty($cards)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Product Specifications</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($cards as $card): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($card['card_title']); ?></label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($card['card_value']); ?>" readonly>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($faults)): ?>
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Product Faults (if any)</h6>
            </div>
            <div class="card-body">
                <?php foreach ($faults as $fault): ?>
                    <div class="mb-3">
                        <label class="form-label">Fault Description</label>
                        <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($fault['fault_description']); ?></textarea>
                        <?php if ($fault['fault_image']): ?>
                            <div class="mt-2">
                                <img src="<?php echo htmlspecialchars($fault['fault_image']); ?>" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Status & Approval</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Current Status</label>
                    <div class="form-control">
                        <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Created At</label>
                    <input type="text" class="form-control" value="<?php echo $product['updated_at']; ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Last Updated</label>
                    <input type="text" class="form-control" value="<?php echo $product['updated_at']; ?>" readonly>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Changing status will notify the vendor.
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <?php if ($product['status'] != 1): ?>
                    <button class="btn btn-success w-100 mb-2" onclick="changeProductStatus(<?php echo $product['id']; ?>, 1)">
                        <i class="fas fa-check me-2"></i> Approve Product
                    </button>
                <?php endif; ?>
                
                <?php if ($product['status'] != 2): ?>
                    <button class="btn btn-danger w-100 mb-2" onclick="changeProductStatus(<?php echo $product['id']; ?>, 2)">
                        <i class="fas fa-times me-2"></i> Reject Product
                    </button>
                <?php endif; ?>
                
                <?php if ($product['status'] != 3): ?>
                    <button class="btn btn-warning w-100 mb-2" onclick="changeProductStatus(<?php echo $product['id']; ?>, 3)">
                        <i class="fas fa-ban me-2"></i> Disable Product
                    </button>
                <?php else: ?>
                    <button class="btn btn-success w-100 mb-2" onclick="changeProductStatus(<?php echo $product['id']; ?>, 1)">
                        <i class="fas fa-check me-2"></i> Enable Product
                    </button>
                <?php endif; ?>
                
                <button class="btn btn-secondary w-100" onclick="confirmDelete(<?php echo $product['id']; ?>)">
                    <i class="fas fa-trash me-2"></i> Delete Product
                </button>
            </div>
        </div>
    </div>
</div>