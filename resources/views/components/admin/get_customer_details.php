<?php
require_once '../mydatabase/conn.php';

if (!isset($_GET['user_id'])) {
    die("User ID not provided");
}

$user_id = intval($_GET['user_id']);

// Get customer basic info
$stmt = $conn->prepare("SELECT u.*, cp.* 
                       FROM users u 
                       LEFT JOIN customer_profile cp ON u.user_id = cp.user_id 
                       WHERE u.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Get customer addresses
$addresses = [];
$stmt = $conn->prepare("SELECT * FROM customer_addresses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get customer orders
$orders = [];
$stmt = $conn->prepare("SELECT o.*, p.name as product_name 
                       FROM orders o 
                       JOIN vendor_products p ON o.product_id = p.id 
                       WHERE o.user_id = ? 
                       ORDER BY o.order_date DESC 
                       LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get customer notes
$notes = [];
$stmt = $conn->prepare("SELECT n.*, a.username as admin_name 
                       FROM customer_notes n 
                       JOIN users a ON n.admin_id = a.user_id 
                       WHERE n.user_id = ? 
                       ORDER BY n.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="<?php echo !empty($customer['profile_image']) ? $customer['profile_image'] : 'https://via.placeholder.com/150'; ?>" 
                     class="rounded-circle mb-3" alt="Customer" width="120">
                <h4><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></h4>
                <p class="text-muted">Member since <?php echo date('M Y', strtotime($customer['created_at'])); ?></p>
                
                <span class="status-badge status-<?php echo $customer['status']; ?>">
                    <?php echo ucfirst($customer['status']); ?>
                    <?php if ($customer['flagged']): ?>
                        <i class="fas fa-flag text-danger ms-1" title="Flagged"></i>
                    <?php endif; ?>
                </span>
                
                <div class="mt-4">
                    <?php if ($customer['status'] == 'active'): ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $customer['user_id']; ?>">
                            <input type="hidden" name="action" value="block">
                            <button class="btn btn-sm btn-danger me-2"><i class="fas fa-ban"></i> Block</button>
                        </form>
                    <?php else: ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $customer['user_id']; ?>">
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
                <p><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($customer['email']); ?></p>
                <p><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($customer['phone']); ?></p>
                
                <?php if (!empty($addresses)): ?>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 
                        <?php echo htmlspecialchars($addresses[0]['address_line1']); ?>,
                        <?php echo htmlspecialchars($addresses[0]['city']); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Stats</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-shopping-cart me-2"></i> <strong>Total Orders:</strong> <?php echo count($orders); ?></p>
                <p><i class="fas fa-money-bill-wave me-2"></i> <strong>Total Spent:</strong> Rs. <?php 
                    $total_spent = array_reduce($orders, function($carry, $order) {
                        return $carry + $order['total_amount'];
                    }, 0);
                    echo number_format($total_spent);
                ?></p>
                <p><i class="fas fa-calendar-alt me-2"></i> <strong>Member Since:</strong> <?php echo date('d M Y', strtotime($customer['created_at'])); ?></p>
                <p><i class="fas fa-flag me-2"></i> <strong>Account Status:</strong> <?php echo ucfirst($customer['status']); ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <ul class="nav nav-tabs" id="customerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders (<?php echo count($orders); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="addresses-tab" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab">Addresses (<?php echo count($addresses); ?>)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">Admin Notes (<?php echo count($notes); ?>)</button>
            </li>
        </ul>
        
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="customerTabsContent">
            <div class="tab-pane fade show active" id="orders" role="tabpanel">
                <?php if (empty($orders)): ?>
                    <div class="alert alert-info">No orders found for this customer</div>
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
                                    <td><span class="badge bg-<?php 
                                        switch($order['fulfillment']) {
                                            case 'delivered': echo 'success'; break;
                                            case 'cancelled': echo 'danger'; break;
                                            default: echo 'warning';
                                        }
                                    ?>"><?php echo ucfirst($order['fulfillment']); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="customer_orders.php?user_id=<?php echo $user_id; ?>" class="btn btn-sm btn-primary">View All Orders</a>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade" id="addresses" role="tabpanel">
                <?php if (empty($addresses)): ?>
                    <div class="alert alert-info">No addresses found for this customer</div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($addresses as $address): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6><?php echo htmlspecialchars($address['address_type']); ?></h6>
                                    <p>
                                        <?php echo htmlspecialchars($address['full_name']); ?><br>
                                        <?php echo htmlspecialchars($address['address_line1']); ?><br>
                                        <?php if (!empty($address['address_line2'])) echo htmlspecialchars($address['address_line2']) . '<br>'; ?>
                                        <?php echo htmlspecialchars($address['city'] . ', ' . $address['state']); ?><br>
                                        <?php echo htmlspecialchars($address['zip_code'] . ', ' . $address['country']); ?>
                                    </p>
                                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($address['phone']); ?></p>
                                    <?php if ($address['is_default']): ?>
                                        <span class="badge bg-success">Default Address</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tab-pane fade" id="notes" role="tabpanel">
                <form method="post" class="mb-3">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="action" value="add_note">
                    <div class="mb-3">
                        <textarea name="note" class="form-control" rows="3" placeholder="Add internal notes about this customer..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Note</button>
                </form>
                
                <?php if (empty($notes)): ?>
                    <div class="alert alert-info">No notes found for this customer</div>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted"><?php echo htmlspecialchars($note['admin_name']); ?> - <?php echo date('d M Y H:i', strtotime($note['created_at'])); ?></small>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                        <input type="hidden" name="action" value="delete_note">
                                        <button type="submit" class="btn btn-sm btn-link text-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($note['note'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>