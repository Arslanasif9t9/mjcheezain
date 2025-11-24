<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="{{ !empty($vendor->profile_picture) ? asset('storage/vendor/profile/' . $vendor->profile_picture) : 'https://via.placeholder.com/150' }}" 
                     class="rounded-circle mb-3 text-center" alt="Vendor" width="120">
                <h4>{{ $vendor->full_name ?? 'N/A' }}</h4>
                <p class="text-muted">{{ $vendor->store_name ?? 'N/A' }}</p>
                <span class="status-badge status-{{ $vendor->status ?? 'unknown' }}">
                    {{ ucfirst($vendor->status ?? 'unknown') }}
                </span>
                
                <div class="mt-4">
                    @if ($vendor->status == 'active')
                        <button class="btn btn-sm btn-danger me-2" onclick="confirmStatusChange({{ $vendor->user_id }}, 'blocked', 'Block')">
                            <i class="fas fa-ban"></i> Block
                        </button>
                    @elseif ($vendor->status == 'pending')
                        <button class="btn btn-sm btn-success me-2" onclick="confirmStatusChange({{ $vendor->user_id }}, 'active', 'Approve')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    @else
                        <button class="btn btn-sm btn-warning me-2" onclick="confirmStatusChange({{ $vendor->user_id }}, 'active', 'Unblock')">
                            <i class="fas fa-lock-open"></i> Unblock
                        </button>
                    @endif
                    {{-- <button class="btn btn-sm btn-warning"><i class="fas fa-key"></i> Reset Password</button> --}}
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-envelope me-2"></i> {{ $vendor->email ?? 'N/A' }}</p>
                <p><i class="fas fa-phone me-2"></i> {{ $vendor->phone ?? 'N/A' }}</p>
                <p><i class="fas fa-map-marker-alt me-2"></i> {{ $vendor->pickup_address ?? 'N/A' }}</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Stats</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-box me-2"></i> <strong>Products:</strong> {{ count($products) }}</p>
                {{-- <p><i class="fas fa-shopping-cart me-2"></i> <strong>Orders:</strong> {{ count($orders) }}</p> --}}
                <p><i class="fas fa-dollar-sign me-2"></i> <strong>Earnings:</strong> Rs. {{ number_format($earnings->total_earnings ?? 0) }}</p>
                <p><i class="fas fa-star me-2"></i> <strong>Rating:</strong> {{ number_format($vendor->rating ?? 0, 1) }}/5</p>
                <p><i class="fas fa-calendar-alt me-2"></i> <strong>Joined:</strong> {{ date('d M Y', strtotime($vendor->created_at)) }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Products ({{ count($products) }})</button>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders ({{ count($orders) }})</button>
            </li> --}}
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">Payments ({{ count($payments) }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">KYC Documents ({{ count($documents) }})</button>
            </li> --}}
        </ul>
        
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="vendorTabsContent">
            <!-- Products Tab -->
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Vendor Products</h5>
                    <a href="/vendor-products/{{ $vendor->user_id }}" class="btn btn-sm btn-primary">View All Products</a>
                </div>
                
                @if ($products->isEmpty())
                    <div class="alert alert-info">No products found for this vendor</div>
                @else
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
                                @foreach ($products as $product)
                                <tr>
                                    <td>#{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>Rs. {{ number_format($product->selling_price) }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->status == 1 ? 'success' : 'warning' }}">
                                            {{ $product->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/product/{{ $product->id }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            <!-- Orders Tab -->
            {{-- <div class="tab-pane fade" id="orders" role="tabpanel">
                @if ($orders->isEmpty())
                    <div class="alert alert-info">No orders found for this vendor</div>
                @else
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
                                @foreach ($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>Rs. {{ number_format($order->total_amount) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->fulfillment == 'delivered' ? 'success' : 
                                            ($order->fulfillment == 'cancelled' ? 'danger' : 'warning')
                                        }}">
                                            {{ ucfirst($order->fulfillment) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ url('admin/vendor/orders/' . $vendor->user_id) }}" class="btn btn-sm btn-primary">View All Orders</a>
                @endif
            </div>
            
            <!-- Payments Tab -->
            <div class="tab-pane fade" id="payments" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Payment Summary</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Total Earnings:</strong> Rs. {{ number_format($earnings->total_earnings ?? 0) }}</p>
                                <p><strong>Pending Payout:</strong> Rs. {{ 
                                    $pending = $earnings->total_earnings ?? 0;
                                    foreach ($payments as $payment) {
                                        if ($payment->status == 'completed') {
                                            $pending -= $payment->amount;
                                        }
                                    }
                                    echo number_format(max(0, $pending));
                                }}</p>
                                <p><strong>Last Payout:</strong> Rs. {{ 
                                    !$payments->isEmpty() ? 
                                    number_format($payments[0]->amount) . ' (' . date('d M Y', strtotime($payments[0]->payment_date)) . ')' : 
                                    '0 (No payments yet)'
                                }}</p>
                                <button class="btn btn-sm btn-primary mt-2" onclick="processPayout({{ $vendor->user_id }})">Process Payout</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Commission Settings</h6>
                            </div>
                            <div class="card-body">
                                <form onsubmit="updateCommission(event, {{ $vendor->user_id }})">
                                    <div class="mb-3">
                                        <label class="form-label">Commission Rate</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="commission_rate" value="15" min="0" max="100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Update Commission</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Payment History</h6>
                @if ($payments->isEmpty())
                    <div class="alert alert-info">No payment history found</div>
                @else
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
                                @foreach ($payments as $payment)
                                <tr>
                                    <td>#{{ $payment->id }}</td>
                                    <td>{{ date('d M Y', strtotime($payment->payment_date)) }}</td>
                                    <td>Rs. {{ number_format($payment->amount) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $payment->status == 'completed' ? 'success' : 
                                            ($payment->status == 'failed' ? 'danger' : 'warning')
                                        }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ url('admin/vendor/payments/' . $vendor->user_id) }}" class="btn btn-sm btn-primary">View All Payments</a>
                @endif
            </div>
            
            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <h5>KYC Documents</h5>
                
                @if ($documents->isEmpty())
                    <div class="alert alert-warning">No documents submitted by this vendor</div>
                @else
                    @php
                        $all_approved = true;
                        foreach ($documents as $doc) {
                            if ($doc->status != 'approved') {
                                $all_approved = false;
                                break;
                            }
                        }
                    @endphp
                    
                    @if ($all_approved)
                        <div class="alert alert-success">
                            Vendor has submitted all required documents and they are approved.
                        </div>
                    @else
                        <div class="alert alert-info">
                            Vendor has submitted documents awaiting approval.
                        </div>
                    @endif
                    
                    <div class="row">
                        @foreach ($documents as $doc)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    @php
                                        $icon = 'fa-file-alt';
                                        $label = 'Document';
                                        switch($doc->document_type) {
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
                                    @endphp
                                    <i class="fas {{ $icon }} fa-3x mb-3 text-primary"></i>
                                    <h6>{{ $label }}</h6>
                                    <p>
                                        <span class="badge bg-{{ 
                                            $doc->status == 'approved' ? 'success' : 
                                            ($doc->status == 'rejected' ? 'danger' : 'warning')
                                        }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </p>
                                    <a href="{{ $doc->document_path }}" target="_blank" class="btn btn-sm btn-primary">View Document</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3">
                        @if (!$all_approved)
                            <button class="btn btn-success me-2" onclick="approveDocuments({{ $vendor->user_id }})">
                                <i class="fas fa-check"></i> Approve All Documents
                            </button>
                        @endif
                        <button class="btn btn-danger" onclick="requestResubmission({{ $vendor->user_id }})">
                            <i class="fas fa-times"></i> Request Resubmission
                        </button>
                    </div>
                @endif
            </div> --}}
        </div>
    </div>
</div>