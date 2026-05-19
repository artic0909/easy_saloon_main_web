@extends('admin.layout.app')

@section('page_title', 'Booking Details - #' . $booking->booking_number)

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="row g-4">
    <!-- Left Column: Customer & Service Info -->
    <div class="col-lg-8">
        <!-- Status Management Card -->
        @if($booking->status == 'completed')
            <div class="card border-0 mb-4 bg-success text-white shadow-lg overflow-hidden position-relative animate-fade-in">
                <div class="card-body p-4 position-relative z-index-1 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <p class="text-white-50 small mb-1 uppercase tracking-wider fw-bold">Current Status ({{ $booking->type }})</p>
                        <h2 class="fw-black mb-0"><i class="bi bi-check-circle-fill me-2"></i>Service Completed</h2>
                    </div>
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-25 px-4 py-2.5 rounded-pill fw-bold">
                        <i class="bi bi-shield-check me-2"></i>COMPLETED
                    </span>
                </div>
                <div class="position-absolute top-0 end-0 translate-middle-y bg-white opacity-10 rounded-circle" style="width: 200px; height: 200px; margin-right: -50px;"></div>
            </div>
        @else
            <div class="card border-0 mb-4 bg-primary text-white shadow-lg overflow-hidden position-relative">
                <div class="card-body p-4 position-relative z-index-1">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="text-white-50 small mb-1 uppercase tracking-wider fw-bold">Current Status ({{ $booking->type }})</p>
                            <h2 class="fw-black mb-0">
                                    @if(!$booking->staff_id)
                                        Broadcasted
                                    @else
                                        {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                    @endif
                            </h2>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <form action="{{ route('staff.bookings.status', $booking->id) }}" method="POST" id="statusForm">
                                @csrf
                                <select name="status" class="form-select border-0 rounded-pill px-4" onchange="confirmStatusChange(this)">
                                    @if(!$booking->staff_id)
                                        <option value="" selected disabled>Available Booking</option>
                                        <option value="Accepted">Accept & Claim Booking</option>
                                    @else
                                        @php 
                                            $s = strtolower($booking->status);
                                            $isVerified = (bool) $booking->verify;
                                        @endphp
                                        <option value="Pending" {{ $s == 'pending' ? 'selected' : '' }} disabled>Pending (Assigned)</option>
                                        <option value="Accepted" {{ $s == 'accepted' ? 'selected' : '' }} disabled>Accepted</option>
                                        <option value="On the way" {{ $s == 'on_the_way' ? 'selected' : '' }} {{ ($s == 'pending' || $s == 'accepted') ? '' : 'disabled' }}>On the way</option>
                                        <option value="Started" {{ $s == 'started' ? 'selected' : '' }} {{ ($s == 'on_the_way' && $isVerified) ? '' : 'disabled' }}>Service Started {{ ($s == 'on_the_way' && !$isVerified) ? '(Requires OTP)' : '' }}</option>
                                        <option value="Completed" {{ $s == 'completed' ? 'selected' : '' }} {{ ($s == 'started') ? '' : 'disabled' }}>Service Completed</option>
                                        <option value="Rejected" {{ $s == 'cancelled' ? 'selected' : '' }} disabled>Reject Booking</option>
                                    @endif
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Decorative circle -->
                <div class="position-absolute top-0 end-0 translate-middle-y bg-white opacity-10 rounded-circle" style="width: 200px; height: 200px; margin-right: -50px;"></div>
            </div>
        @endif

        <div class="card border-0 mb-4">
            <div class="card-header"><h5 class="mb-0 fw-bold">Service Information</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="rounded-start">Service Name</th>
                                <th>Category</th>
                                <th class="text-end rounded-end">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($booking->items as $item)
                             <tr>
                                 <td>
                                     @if($item->item_type == 'package')
                                         <div class="fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>{{ $item->package->name ?? 'Deleted Package' }}</div>
                                         <div class="small text-muted mt-1">Full Service Bundle</div>
                                     @else
                                         <div class="fw-bold">{{ $item->service->name ?? 'Deleted Service' }}</div>
                                         @if($item->service && $item->service->subCategory && $item->service->subCategory->equipment->count() > 0)
                                             <div class="mt-1 d-flex flex-wrap gap-1">
                                                 @foreach($item->service->subCategory->equipment as $eq)
                                                     <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 py-0 px-2" style="font-size: 0.6rem;">
                                                         <i class="bi bi-tools me-1"></i>{{ $eq->name }}
                                                     </span>
                                                 @endforeach
                                             </div>
                                         @endif
                                     @endif
                                 </td>
                                 <td>
                                     @if($item->item_type == 'package')
                                         <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">Bundle</span>
                                     @else
                                         <span class="badge bg-light text-muted border">{{ $item->service->category->name ?? 'N/A' }}</span>
                                     @endif
                                 </td>
                                 <td class="text-end fw-bold">
                                     <div class="d-flex flex-column align-items-end">
                                         @if($item->item_type == 'package' && $item->package)
                                             <div class="d-flex align-items-baseline gap-2 justify-content-end">
                                                 <span class="text-dark">₹{{ number_format($item->price, 0) }}</span>
                                                 <small class="text-muted text-decoration-line-through fw-normal" style="font-size: 0.7rem;">₹{{ number_format($item->package->original_price, 0) }}</small>
                                             </div>
                                         @elseif($item->item_type == 'service' && $item->service)
                                             @if($item->price > 0)
                                                 <div class="d-flex align-items-baseline gap-2 justify-content-end">
                                                     <span class="text-dark">₹{{ number_format($item->price, 0) }}</span>
                                                     <small class="text-muted text-decoration-line-through fw-normal" style="font-size: 0.7rem;">₹{{ number_format($item->service->original_price, 0) }}</small>
                                                 </div>
                                             @else
                                                 <span class="text-success small fw-normal mb-1">Included</span>
                                                 <div class="d-flex align-items-baseline gap-2 justify-content-end opacity-50">
                                                     <small class="fw-bold text-dark" style="font-size: 0.65rem;">₹{{ number_format($item->service->sale_price, 0) }}</small>
                                                     <small class="text-decoration-line-through text-muted" style="font-size: 0.6rem;">₹{{ number_format($item->service->original_price, 0) }}</small>
                                                 </div>
                                             @endif
                                         @endif
                                     </div>
                                 </td>
                             </tr>
                             @endforeach
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="2" class="text-end text-muted">Payable Amount</td>
                                <td class="text-end fw-black fs-4 text-primary">₹{{ number_format($booking->payable_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @php
            $equipments = $booking->equipment;
            if (is_string($equipments)) {
                $equipments = json_decode($equipments, true);
            }
        @endphp

        @if($equipments && count($equipments) > 0)
        <div class="card border-0 mb-4 shadow-sm">
            <div class="card-header bg-info bg-opacity-10 py-3">
                <h5 class="mb-0 fw-bold text-info"><i class="bi bi-tools me-2"></i>Equipments Required</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($equipments as $item)
                        <span class="badge bg-light text-info border border-info border-opacity-25 px-4 py-2 rounded-pill fs-6">
                            {{ $item }}
                        </span>
                    @endforeach
                </div>
                <p class="text-muted small mt-3 mb-0">Please ensure you carry all the listed equipment for this service.</p>
            </div>
        </div>
        @endif

        <div class="card border-0">
            <div class="card-header"><h5 class="mb-0 fw-bold">Service Location</h5></div>
            <div class="card-body">
                @if($booking->address)
                <div class="d-flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-4 p-3 text-primary">
                            <i class="bi bi-geo-alt-fill fs-2"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-2">{{ $booking->address->address_type ?? $booking->address->title ?? 'Home' }} Address</h6>
                        <p class="text-muted mb-3">
                            {{ $booking->address->full_address ?? $booking->address->address_line1 ?? '' }}, 
                            {{ $booking->address->city->name ?? '' }}, 
                            {{ $booking->address->state->name ?? '' }} 
                            @if($booking->address->pincode) - {{ $booking->address->pincode }} @endif
                        </p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($booking->address->full_address ?? $booking->address->address_line1 ?? '') . ' ' . ($booking->address->city->name ?? '')) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4">
                            <i class="bi bi-map me-2"></i> Open in Maps
                        </a>
                    </div>
                </div>
                @else
                <p class="text-muted">No specific address provided.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Customer Details -->
    <div class="col-lg-4">
        <div class="card border-0 mb-4 text-center p-4">
            <div class="avatar-md bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold fs-2 mb-3" style="width: 100px; height: 100px;">
                {{ substr($booking->user->name, 0, 1) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $booking->user->name }}</h5>
            <p class="text-muted small mb-4">Customer since {{ $booking->user->created_at->format('M Y') }}</p>
            
            <div class="d-flex gap-2 justify-content-center">
                <a href="tel:{{ $booking->user->phone }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-telephone-fill me-2"></i> Call
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->user->phone) }}" target="_blank" class="btn btn-success rounded-pill px-4" style="background: #25D366; border: none;">
                    <i class="bi bi-whatsapp me-2"></i> WhatsApp
                </a>
            </div>
        </div>

        <!-- OTP Verification Card -->
        @if($booking->otp)
            <div class="card border-0 mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-shield-lock me-2 text-warning"></i> OTP Verification
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->verify)
                        <div class="text-center py-3">
                            <div class="avatar-sm bg-success bg-opacity-10 text-success rounded-circle mx-auto d-flex align-items-center justify-center mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-shield-check fs-3"></i>
                            </div>
                            <h6 class="fw-bold text-success mb-1">OTP Verified</h6>
                            <p class="text-muted small mb-0">This booking is verified and secure.</p>
                        </div>
                    @else
                        <form action="{{ route('staff.bookings.verify_otp', $booking->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Enter Customer's OTP</label>
                                <input type="text" name="otp" class="form-control rounded-pill text-center tracking-widest fw-black" placeholder="XXXX" required maxlen="6" style="font-size: 1.25rem;">
                            </div>
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-white shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i> Verify OTP
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <div class="card border-0">
            <div class="card-header"><h5 class="mb-0 fw-bold">Booking Details</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Booking Date</span>
                    <span class="fw-bold">{{ date('M d, Y', strtotime($booking->booking_date)) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Time Slot</span>
                    <span class="fw-bold">{{ $booking->time_slot }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Payment Mode</span>
                    <span class="badge bg-light text-dark border">{{ $booking->payment_type ?? 'COD' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Payment Status</span>
                    <span class="badge {{ $booking->is_paid ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                        {{ $booking->is_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>
                @if($booking->otp)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">OTP Verification</span>
                    <span class="badge {{ $booking->verify ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-10' : 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10' }} py-1 px-2.5">
                        {{ $booking->verify ? 'Verified' : 'Not Verified' }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function confirmStatusChange(select) {
    const status = select.value;
    Swal.fire({
        title: 'Update Booking Status?',
        text: `Are you sure you want to change the status to "${status}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'var(--admin-accent)',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        customClass: {
            confirmButton: 'rounded-pill px-4',
            cancelButton: 'rounded-pill px-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('statusForm').submit();
        } else {
            // Revert selection
            location.reload();
        }
    });
}
</script>
@endsection
@endsection
