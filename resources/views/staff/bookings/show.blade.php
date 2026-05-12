@extends('admin.layout.app')

@section('page_title', 'Booking Details - #' . $booking->booking_number)

@section('content')
<div class="row g-4">
    <!-- Left Column: Customer & Service Info -->
    <div class="col-lg-8">
        <!-- Status Management Card -->
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
                                    @php $s = $booking->status; @endphp
                                    <option value="Pending" {{ $s == 'pending' ? 'selected' : '' }}>Pending (Assigned)</option>
                                    <option value="Accepted" {{ $s == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="On the way" {{ $s == 'on_the_way' ? 'selected' : '' }}>On the way</option>
                                    <option value="Started" {{ $s == 'started' ? 'selected' : '' }}>Service Started</option>
                                    <option value="Completed" {{ $s == 'completed' ? 'selected' : '' }}>Service Completed</option>
                                    <option value="Rejected" {{ $s == 'cancelled' ? 'selected' : '' }}>Reject Booking</option>
                                @endif
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="position-absolute top-0 end-0 translate-middle-y bg-white opacity-10 rounded-circle" style="width: 200px; height: 200px; margin-right: -50px;"></div>
        </div>

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
                                 </td>
                                 <td><span class="badge bg-light text-muted border">{{ $item->service->category->name ?? 'N/A' }}</span></td>
                                 <td class="text-end fw-bold">₹{{ number_format($item->price, 2) }}</td>
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
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Payment Status</span>
                    <span class="badge {{ $booking->is_paid ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                        {{ $booking->is_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>
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
