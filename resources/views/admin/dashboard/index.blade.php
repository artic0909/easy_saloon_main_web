@extends('admin.layout.app')

@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 p-4 shadow-sm">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-2">Total Customers</h6>
            <h2 class="fw-black mb-1">{{ $stats['users'] }}</h2>
            <div class="mt-3 small text-success">
                <i class="bi bi-arrow-up-right me-1"></i> 12% increase <span class="text-muted fw-normal ms-1">vs last month</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 p-4 shadow-sm">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-2">Active Bookings</h6>
            <h2 class="fw-black mb-1">{{ $stats['bookings'] }}</h2>
            <div class="mt-3 small text-primary">
                <i class="bi bi-clock-history me-1"></i> 5 Pending <span class="text-muted fw-normal ms-1">needs attention</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 p-4 shadow-sm">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-2">Monthly Revenue</h6>
            <h2 class="fw-black mb-1">₹{{ number_format($stats['monthly_revenue'], 2) }}</h2>
            <div class="mt-3 small text-success">
                <i class="bi bi-calendar-event me-1"></i> Current Month
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 p-4 shadow-sm">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-wallet2"></i>
            </div>
            <h6 class="text-muted fw-bold small text-uppercase tracking-wider mb-2">Total Transactions</h6>
            <h2 class="fw-black mb-1">{{ $stats['transactions_count'] }}</h2>
            <div class="mt-3 small text-muted">
                All time payment records
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="card border-0 h-100 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0">Recent Appointments</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase tracking-widest text-muted fw-bold">Client</th>
                                <th class="py-3 border-0 small text-uppercase tracking-widest text-muted fw-bold">Service</th>
                                <th class="py-3 border-0 small text-uppercase tracking-widest text-muted fw-bold">Date</th>
                                <th class="py-3 border-0 small text-uppercase tracking-widest text-muted fw-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.bookings.show', $booking->id) }}'">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 11px;">
                                            {{ substr($booking->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $booking->user->name }}</div>
                                            <div class="text-muted" style="font-size: 10px;">{{ $booking->user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small fw-medium">
                                    @if($booking->items->isNotEmpty())
                                        {{ $booking->items->first()->package_id ? $booking->items->first()->package->name : $booking->items->first()->service->name }}
                                    @else
                                        <span class="text-muted">No Items</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill 
                                        @if($booking->status == 'completed') bg-success-subtle text-success
                                        @elseif($booking->status == 'pending') bg-warning-subtle text-warning
                                        @elseif($booking->status == 'cancelled') bg-danger-subtle text-danger
                                        @else bg-primary-subtle text-primary @endif px-3 py-2" style="font-size: 10px;">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-4">
        <div class="card border-0 h-100 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0">Recent Payments</h5>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">History</a>
            </div>
            <div class="card-body p-0">
                <div class="d-flex flex-column">
                    @foreach($recentTransactions as $txn)
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm @if($txn->status == 'success') bg-success @else bg-warning @endif text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 10px;">
                                <i class="bi bi-currency-rupee"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark small">₹{{ number_format($txn->amount, 2) }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $txn->user->name ?? 'Guest' }}</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge @if($txn->status == 'success') text-success @else text-warning @endif p-0" style="font-size: 9px;">{{ strtoupper($txn->status) }}</span>
                            <div class="text-muted" style="font-size: 9px;">{{ $txn->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
