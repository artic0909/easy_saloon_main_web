@extends('admin.layout.app')

@section('page_title', 'Transaction Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-dark">Payment Details</h5>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Status Header -->
                <div class="px-4 py-4 @if($transaction->status == 'success') bg-success-subtle @elseif($transaction->status == 'pending') bg-warning-subtle @else bg-danger-subtle @endif text-center">
                    <div class="display-6 fw-bold mb-1">₹{{ number_format($transaction->amount, 2) }}</div>
                    <div class="fw-bold text-uppercase tracking-wider small @if($transaction->status == 'success') text-success @elseif($transaction->status == 'pending') text-warning @else text-danger @endif">
                        {{ $transaction->status }}
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Transaction ID</label>
                            <p class="fw-bold text-dark mb-0">{{ $transaction->transaction_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Payment Mode</label>
                            <p class="fw-bold text-dark mb-0">{{ strtoupper($transaction->payment_mode) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Payment Date</label>
                            <p class="fw-bold text-dark mb-0">{{ $transaction->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Customer Info</label>
                            <p class="fw-bold text-dark mb-0">{{ $transaction->user->name ?? 'Guest' }}</p>
                            <span class="small text-muted">{{ $transaction->user->email ?? '' }}</span>
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    <h6 class="fw-bold text-dark mb-3">Linked Booking</h6>
                    @if($transaction->booking)
                        <div class="p-3 bg-light rounded-3 border border-dashed">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-dark">Standard Booking #{{ $transaction->booking->booking_number }}</div>
                                    <div class="small text-muted">{{ $transaction->booking->service_type }} Appointment</div>
                                </div>
                                <a href="{{ route('admin.bookings.show', $transaction->booking->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill">View Booking</a>
                            </div>
                        </div>
                    @elseif($transaction->customBooking)
                        <div class="p-3 bg-light rounded-3 border border-dashed">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-dark">Custom Package #{{ $transaction->customBooking->booking_number }}</div>
                                    <div class="small text-muted">Bespoke Saloon Experience</div>
                                </div>
                                <a href="{{ route('admin.custom_bookings.show', $transaction->customBooking->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill">View Package</a>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small">No linked booking found.</div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-light border-0 py-3 text-center">
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print Receipt</button>
            </div>
        </div>
    </div>
</div>
@endsection
