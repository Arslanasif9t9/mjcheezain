@php
    // $earnings is optional — the controller may not pass it. Null-safe default
    // prevents the modal from erroring out when it is missing.
    $earnings = $earnings ?? null;
@endphp
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="{{ !empty($vendor->profile_picture) ? asset('storage/vendor/profile/' . $vendor->profile_picture) : 'https://via.placeholder.com/150' }}"
                     class="rounded-circle mb-3 text-center" alt="Vendor" width="120" height="120" style="object-fit: cover; border: 3px solid #FDE7EE;">
                <h4 class="fw-bold">{{ $vendor->full_name ?? 'N/A' }}</h4>
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
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-address-book me-2" style="color:#E85D85;"></i>Contact Information</h6>
            </div>
            <div class="card-body">
                <p class="text-break"><i class="fas fa-envelope me-2" style="color:#E85D85;"></i> {{ $vendor->email ?? 'N/A' }}</p>
                <p><i class="fas fa-phone me-2" style="color:#E85D85;"></i> {{ $vendor->phone ?? 'N/A' }}</p>
                <p class="mb-0"><i class="fas fa-map-marker-alt me-2" style="color:#E85D85;"></i> {{ $vendor->pickup_address ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-simple me-2" style="color:#E85D85;"></i>Quick Stats</h6>
            </div>
            <div class="card-body">
                <p><i class="fas fa-box me-2" style="color:#E85D85;"></i> <strong>Products:</strong> {{ count($products) }}</p>
                <p><i class="fas fa-wallet me-2" style="color:#E85D85;"></i> <strong>Earnings:</strong> Rs. {{ number_format($earnings->total_earnings ?? 0) }}</p>
                <p><i class="fas fa-star me-2" style="color:#E85D85;"></i> <strong>Rating:</strong> {{ number_format($vendor->rating ?? 0, 1) }}/5</p>
                <p class="mb-0"><i class="fas fa-calendar-alt me-2" style="color:#E85D85;"></i> <strong>Joined:</strong> {{ date('d M Y', strtotime($vendor->created_at)) }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Products ({{ count($products) }})</button>
            </li>
        </ul>

        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="vendorTabsContent">
            <!-- Products Tab -->
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold">Vendor Products</h5>
                    <a href="/vendor-products/{{ $vendor->user_id }}" class="btn btn-sm text-white" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">View All Products</a>
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
                                @php
                                    // vendor_products uses a string `position` column
                                    // (pending / approved / rejected / disable / draft).
                                    $position = strtolower($product->position ?? 'pending');
                                    $positionColor = match ($position) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'pending'  => 'warning',
                                        default    => 'secondary',
                                    };
                                @endphp
                                <tr>
                                    <td>#{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>Rs. {{ number_format($product->selling_price) }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $positionColor }}">
                                            {{ ucfirst($position) }}
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
        </div>
    </div>
</div>
