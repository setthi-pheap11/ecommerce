@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <!-- Dashboard Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Products</h6>
                            <h3 class="mb-0">{{ $stats['total_products'] ?? 0 }}</h3>
                        </div>
                        <div class="dashboard-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Orders</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                        </div>
                        <div class="dashboard-icon bg-success">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h3 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                        </div>
                        <div class="dashboard-icon bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                        </div>
                        <div class="dashboard-icon bg-warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recent_orders) && $recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($order->status === 'pending') bg-warning
                                                    @elseif($order->status === 'processing') bg-info
                                                    @elseif($order->status === 'shipped') bg-primary
                                                    @elseif($order->status === 'delivered') bg-success
                                                    @else bg-danger
                                                    @endif
                                                ">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Add New Category
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-info">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-warning">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Products</h5>
                </div>
                <div class="card-body">
                    @if(isset($recent_products) && $recent_products->count() > 0)
                        @foreach($recent_products as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                    <small class="text-muted">${{ number_format($product->price, 2) }}</small>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                                View All Products
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-box fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No products yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sales Overview</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Sales chart will be displayed here</p>
                        <small class="text-muted">Integration with Chart.js can be added for detailed analytics</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    console.log('Dashboard loaded successfully');
</script>
@endpush
