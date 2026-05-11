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

<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Recent Transactions</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Transaction ID</th>
                        <th class="py-3 border-0">Customer</th>
                        <th class="py-3 border-0">Booking #</th>
                        <th class="py-3 border-0">Amount</th>
                        <th class="py-3 border-0">Method</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-4">
                            <span class="font-monospace text-primary fw-bold">{{ $transaction->transaction_id }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $transaction->user->name }}</div>
                        </td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $transaction->booking_id) }}" class="text-decoration-none fw-bold text-dark">
                                #{{ $transaction->booking->booking_number }}
                            </a>
                        </td>
                        <td class="fw-black text-dark">₹{{ number_format($transaction->amount) }}</td>
                        <td>
                            <span class="text-uppercase small fw-bold text-muted">{{ $transaction->payment_method }}</span>
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
                        <td colspan="7" class="py-5 text-center text-muted">No transactions found.</td>
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
