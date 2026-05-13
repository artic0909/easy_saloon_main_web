@extends('admin.layout.app')

@section('page_title', 'Financial Reports & Transactions')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white border-0 shadow-lg p-4">
            <h6 class="text-white-50 small fw-bold text-uppercase tracking-widest mb-2">Lifetime Revenue</h6>
            <h2 class="fw-black mb-0">₹{{ number_format($revenueStats['total']) }}</h2>
            <div class="mt-3 small">
                <i class="bi bi-graph-up"></i> All time earnings
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark text-white border-0 shadow-lg p-4">
            <h6 class="text-white-50 small fw-bold text-uppercase tracking-widest mb-2">Monthly Revenue</h6>
            <h2 class="fw-black mb-0">₹{{ number_format($revenueStats['this_month']) }}</h2>
            <div class="mt-3 small text-warning">
                Current month: {{ now()->format('F Y') }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm p-4">
            <h6 class="text-secondary small fw-bold text-uppercase tracking-widest mb-2">Today's Revenue</h6>
            <h2 class="fw-black mb-0 text-success">₹{{ number_format($revenueStats['today']) }}</h2>
            <div class="mt-3 small text-muted">
                Transactions: {{ now()->format('d M, Y') }}
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0 text-dark">Filter Reports</h5>
            <div class="small text-muted">Generate insights by date range</div>
        </div>
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Generate</button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-light border flex-grow-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Transaction Analysis</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">SL</th>
                        <th class="py-3 border-0">Transaction ID</th>
                        <th class="py-3 border-0">Customer</th>
                        <th class="py-3 border-0">Booking #</th>
                        <th class="py-3 border-0">Amount</th>
                        <th class="py-3 border-0">Mode</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-4">
                            {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <span class="font-monospace text-primary fw-bold">{{ $transaction->transaction_id }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $transaction->user->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($transaction->booking)
                                <a href="{{ route('admin.bookings.show', $transaction->booking_id) }}" class="text-decoration-none fw-bold text-dark">
                                    #{{ $transaction->booking->booking_number }}
                                </a>
                            @elseif($transaction->customBooking)
                                <a href="{{ route('admin.custom_bookings.show', $transaction->custom_booking_id) }}" class="text-decoration-none fw-bold text-dark">
                                    #{{ $transaction->customBooking->booking_number }} (Custom)
                                </a>
                            @else
                                <span class="text-muted small">N/A</span>
                            @endif
                        </td>
                        <td class="fw-black text-dark">₹{{ number_format($transaction->amount) }}</td>
                        <td>
                            <span class="text-uppercase small fw-bold text-muted">{{ $transaction->payment_mode }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill {{ $transaction->status == 'success' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-4 text-end text-muted small">
                            {{ $transaction->created_at->format('d M, Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-5 text-center text-muted">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
