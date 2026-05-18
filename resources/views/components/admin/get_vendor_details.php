<?php
require_once '../mydatabase/conn.php';

// if (!isset($_GET['user_id'])) {
//     die("Vendor ID not provided");
// }

$user_id = intval($_GET['user_id']);

// Get vendor basic info
$stmt = $conn->prepare("SELECT u.*, vbi.*, vsd.*, va.* 
                       FROM users u
                       LEFT JOIN vendor_basic_info vbi ON u.user_id = vbi.user_id
                       LEFT JOIN vendor_store_details vsd ON u.user_id = vsd.user_id
                       LEFT JOIN vendor_address va ON u.user_id = va.user_id
                       WHERE u.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$vendor = $stmt->get_result()->fetch_assoc();

// Get vendor products
$products = [];
$stmt = $conn->prepare("SELECT * FROM vendor_products WHERE user_id = ? LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get vendor orders
$orders = [];
$stmt = $conn->prepare("SELECT o.*, p.name as product_name 
                       FROM orders o 
                       JOIN vendor_products p ON o.product_id = p.id 
                       WHERE o.vendor_id = ? 
                       ORDER BY o.order_date DESC 
                       LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get vendor documents
$documents = [];
$stmt = $conn->prepare("SELECT * FROM vendor_documents WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$documents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get vendor payments
$payments = [];
$stmt = $conn->prepare("SELECT * FROM vendor_payments WHERE user_id = ? ORDER BY payment_date DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$payments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total earnings
$stmt = $conn->prepare("SELECT SUM(total_amount) as total_earnings FROM orders WHERE vendor_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$earnings = $stmt->get_result()->fetch_assoc();
?>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="<?php echo !empty($vendor['profile_picture']) ? "../vendor/" . $vendor['profile_picture'] : 'https://via.placeholder.com/150'; ?>" 
                     class="rounded-circle mb-3" alt="Vendor" width="120">
                <h4><?php echo htmlspecialchars($vendor['full_name']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($vendor['store_name']); ?></p>
                <span class="status-badge status-<?php echo $vendor['status']; ?>">
                    <?php echo ucfirst($vendor['status']); ?>
                </span>
                
                <div class="mt-4">
                    <?php if ($vendor['status'] == 'active'): ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $vendor['user_id']; ?>">
                            <input type="hidden" name="action" value="block">
                            <button class="btn btn-sm btn-danger me-2"><i class="fas fa-ban"></i> Block</button>
                        </form>
                    <?php elseif ($vendor['status'] == 'pending'): ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $vendor['user_id']; ?>">
                            <input type="hidden" name="action" value="approve">
                            <button class="btn btn-sm btn-success me-2"><i class="fas fa-check"></i> Approve</button>
                        </form>
                    <?php else: ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $vendor['user_id']; ?>">
                            <input type="hidden" name="action" value="unblock">
                            <button class="btn btn-sm btn-warning me-2"><i class="fas fa-lock-open"></i> Unblock</button>
                        </form>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-warning"><i class="fas fa-key"></i> Reset Password</button>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($vendor['email']); ?></p>
                <p><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($vendor['phone']); ?></p>
                <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($vendor['pickup_address']); ?></p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Stats</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-box me-2"></i> <strong>Products:</strong> <?php echo count($products); ?></p>
                <p><i class="fas fa-shopping-cart me-2"></i> <strong>Orders:</strong> <?php echo count($orders); ?></p>
                <p><i class="fas fa-dollar-sign me-2"></i> <strong>Earnings:</strong> Rs. <?php echo number_format($earnings['total_earnings'] ?? 0); ?></p>
                <p><i class="fas fa-star me-2"></i> <strong>Rating:</strong> <?php echo number_format($vendor['rating'] ?? 0, 1); ?>/5</p>
                <p><i class="fas fa-calendar-alt me-2"></i> <strong>Joined:</strong> <?php echo date('d M Y', strtotime($vendor['created_at'])); ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Products (<?php echo count($products); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders (<?php echo count($orders); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">Payments (<?php echo count($payments); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">KYC Documents (<?php echo count($documents); ?>)</button>
            </li>
        </ul>
        
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="vendorTabsContent">
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Vendor Products</h5>
                    <a href="vendor_products.php?user_id=<?php echo $user_id; ?>" class="btn btn-sm btn-primary">View All Products</a>
                </div>
                
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">No products found for this vendor</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>#<?php echo $product['id']; ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td>Rs. <?php echo number_format($product['selling_price']); ?></td>
                                    <td><?php echo $product['quantity']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo ($product['status'] == 1) ? 'success' : 'warning';
                                        ?>">
                                            <?php echo ($product['status'] == 1) ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <?php if (empty($orders)): ?>
                    <div class="alert alert-info">No orders found for this vendor</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td>Rs. <?php echo number_format($order['total_amount']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($order['fulfillment']) {
                                                case 'delivered': echo 'success'; break;
                                                case 'cancelled': echo 'danger'; break;
                                                default: echo 'warning';
                                            }
                                        ?>">
                                            <?php echo ucfirst($order['fulfillment']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="vendor_orders.php?user_id=<?php echo $user_id; ?>" class="btn btn-sm btn-primary">View All Orders</a>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade" id="payments" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Payment Summary</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Total Earnings:</strong> Rs. <?php echo number_format($earnings['total_earnings'] ?? 0); ?></p>
                                <p><strong>Pending Payout:</strong> Rs. <?php 
                                    $pending = $earnings['total_earnings'] ?? 0;
                                    foreach ($payments as $payment) {
                                        if ($payment['status'] == 'completed') {
                                            $pending -= $payment['amount'];
                                        }
                                    }
                                    echo number_format(max(0, $pending));
                                ?></p>
                                <p><strong>Last Payout:</strong> Rs. <?php 
                                    if (!empty($payments)) {
                                        echo number_format($payments[0]['amount']) . ' (' . date('d M Y', strtotime($payments[0]['payment_date'])) . ')';
                                    } else {
                                        echo '0 (No payments yet)';
                                    }
                                ?></p>
                                <form method="post" class="mt-2">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <input type="hidden" name="action" value="process_payment">
                                    <button class="btn btn-sm btn-primary">Process Payout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Commission Settings</h6>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Commission Rate</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="commission_rate" value="15" min="0" max="100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <input type="hidden" name="action" value="update_commission">
                                    <button class="btn btn-sm btn-primary">Update Commission</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Payment History</h6>
                <?php if (empty($payments)): ?>
                    <div class="alert alert-info">No payment history found</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td>#<?php echo $payment['id']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
                                    <td>Rs. <?php echo number_format($payment['amount']); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($payment['status']) {
                                                case 'completed': echo 'success'; break;
                                                case 'failed': echo 'danger'; break;
                                                default: echo 'warning';
                                            }
                                        ?>">
                                            <?php echo ucfirst($payment['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="vendor_payments.php?user_id=<?php echo $user_id; ?>" class="btn btn-sm btn-primary">View All Payments</a>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <h5>KYC Documents</h5>
                
                <?php if (empty($documents)): ?>
                    <div class="alert alert-warning">No documents submitted by this vendor</div>
                <?php else: ?>
                    <?php 
                    $all_approved = true;
                    foreach ($documents as $doc) {
                        if ($doc['status'] != 'approved') {
                            $all_approved = false;
                            break;
                        }
                    }
                    
                    if ($all_approved): ?>
                        <div class="alert alert-success">
                            Vendor has submitted all required documents and they are approved.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Vendor has submitted documents awaiting approval.
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <?php foreach ($documents as $doc): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <?php 
                                    $icon = 'fa-file-alt';
                                    $label = 'Document';
                                    switch($doc['document_type']) {
                                        case 'id_proof': 
                                            $icon = 'fa-id-card';
                                            $label = 'ID Proof';
                                            break;
                                        case 'address_proof': 
                                            $icon = 'fa-home';
                                            $label = 'Address Proof';
                                            break;
                                        case 'tax_document': 
                                            $icon = 'fa-file-invoice-dollar';
                                            $label = 'Tax Document';
                                            break;
                                    }
                                    ?>
                                    <i class="fas <?php echo $icon; ?> fa-3x mb-3 text-primary"></i>
                                    <h6><?php echo $label; ?></h6>
                                    <p>
                                        <span class="badge bg-<?php 
                                            switch($doc['status']) {
                                                case 'approved': echo 'success'; break;
                                                case 'rejected': echo 'danger'; break;
                                                default: echo 'warning';
                                            }
                                        ?>">
                                            <?php echo ucfirst($doc['status']); ?>
                                        </span>
                                    </p>
                                    <a href="<?php echo $doc['document_path']; ?>" target="_blank" class="btn btn-sm btn-primary">View Document</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-3">
                        <?php if (!$all_approved): ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="action" value="approve_documents">
                                <button class="btn btn-success me-2"><i class="fas fa-check"></i> Approve All Documents</button>
                            </form>
                        <?php endif; ?>
                        <button class="btn btn-danger"><i class="fas fa-times"></i> Request Resubmission</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>