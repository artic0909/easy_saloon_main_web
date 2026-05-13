@extends('admin.layout.app')

@section('page_title', 'Payment History')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">Transaction Records</h5>
        </div>
        
        <!-- Filters Area -->
        <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Search Transaction</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="TXN ID, Customer Name or Email..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase">Payment Mode</label>
                <select name="payment_mode" class="form-select">
                    <option value="">All Modes</option>
                    <option value="razorpay" {{ request('payment_mode') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                    <option value="wallet" {{ request('payment_mode') == 'wallet' ? 'selected' : '' }}>Wallet</option>
                    <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-light border flex-grow-1"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4 py-3 border-0">SL</th>
                        <th class="py-3 border-0">TXN ID</th>
                        <th class="py-3 border-0">Customer</th>
                        <th class="py-3 border-0">Booking #</th>
                        <th class="py-3 border-0">Amount</th>
                        <th class="py-3 border-0">Mode</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="py-3 border-0">Date</th>
                        <th class="px-4 py-3 border-0 text-end">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td class="px-4">
                            {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <code class="text-primary fw-bold">{{ $txn->transaction_id }}</code>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $txn->user->name ?? 'Guest' }}</div>
                            <div class="small text-muted">{{ $txn->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            @if($txn->booking)
                                <a href="{{ route('admin.bookings.show', $txn->booking->id) }}" class="text-decoration-none fw-bold">#{{ $txn->booking->booking_number }}</a>
                            @elseif($txn->customBooking)
                                <a href="{{ route('admin.custom_bookings.show', $txn->customBooking->id) }}" class="text-decoration-none fw-bold">#{{ $txn->customBooking->booking_number }} (Custom)</a>
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">₹{{ number_format($txn->amount, 2) }}</div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary text-uppercase px-2">
                                {{ $txn->payment_mode }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($txn->status == 'success')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Success
                                </span>
                            @elseif($txn->status == 'pending')
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">
                                    <i class="bi bi-clock-fill me-1"></i> Pending
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Failed
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $txn->created_at->format('d M Y') }}</div>
                            <div class="small text-muted">{{ $txn->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 text-end">
                            <a href="{{ route('admin.payments.show', $txn->id) }}" class="btn btn-sm btn-light border-0">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt display-4 d-block mb-3"></i>
                            No transactions found matching your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
