@extends('admin.layout.app')

@section('page_title', 'Manage Custom Booking #' . $booking->booking_number)

@section('content')
<div class="row g-4">
    <!-- Booking Details -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Custom Appointment Information</h5>
                <span class="badge bg-info text-white">Bespoke Selection</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Customer Details</label>
                        <div class="mt-2 p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold mb-1">{{ $booking->user->name }}</h6>
                            <p class="text-muted mb-1 small text-truncate"><i class="bi bi-envelope me-2"></i>{{ $booking->user->email }}</p>
                            <p class="text-muted mb-0 small"><i class="bi bi-telephone me-2"></i>{{ $booking->user->phone }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Service Schedule</label>
                        <div class="mt-2 p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold mb-1"><i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}</h6>
                            <p class="text-muted mb-0 small"><i class="bi bi-clock me-2 text-uppercase"></i>{{ $booking->time_slot }} Slot</p>
                            <p class="small text-primary mt-1 mb-0"><i class="bi bi-hourglass-split me-1"></i>Est. Duration: {{ $booking->total_duration }} Mins</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Payment Information</label>
                        <div class="mt-2 p-3 bg-light rounded-3 h-100">
                            <p class="mb-1 small"><span class="text-muted">Method:</span> <strong class="text-uppercase text-dark">{{ $booking->pay_type ?? $booking->payment_type ?? 'online' }}</strong></p>
                            <p class="mb-1 small">
                                <span class="text-muted">Status:</span> 
                                <span class="badge {{ $booking->is_paid ? 'bg-success' : 'bg-warning' }} px-2.5 py-1 text-xs">
                                    {{ $booking->is_paid ? 'Paid' : 'Unpaid' }}
                                </span>
                            </p>
                            @if($booking->coupon_code)
                                <p class="mb-0 small"><span class="text-muted">Coupon:</span> <strong class="text-success">{{ $booking->coupon_code }}</strong></p>
                            @endif
                        </div>
                    </div>
                    @if($booking->service_type == 'home' && $booking->address)
                    <div class="col-12">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Service Location (Home)</label>
                        <div class="mt-2 p-3 bg-light rounded-3">
                            <h6 class="fw-bold mb-1">{{ $booking->address->title }}</h6>
                            <p class="text-muted mb-0">{{ $booking->address->full_address }}, {{ $booking->address->city->name }}, {{ $booking->address->state->name }}</p>
                        </div>
                    </div>
                    @endif

                    @if($booking->equipment && count($booking->equipment) > 0)
                    <div class="col-12">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Equipments Required</label>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($booking->equipment as $item)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill">
                                    <i class="bi bi-tools me-2"></i>{{ $item }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Custom Selected Services</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-2">Service Name</th>
                            <th class="py-2">Duration</th>
                            <th class="px-4 py-2 text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->services as $service)
                        <tr class="border-bottom">
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $service->name }}</div>
                                <div class="small text-muted">{{ $service->category->name ?? 'Service' }}</div>
                            </td>
                            <td>{{ $service->duration_minutes }} Mins</td>
                            <td class="px-4 text-end fw-bold">₹{{ number_format($service->sale_price) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light text-muted small">
                            <td colspan="2" class="px-4 py-2 fw-bold text-end">Subtotal Bespoke Price</td>
                            <td class="px-4 py-2 text-end fw-bold">₹{{ number_format($booking->total_price, 2) }}</td>
                        </tr>
                        @if($booking->discount_amount > 0)
                        <tr class="bg-light text-success small">
                            <td colspan="2" class="px-4 py-2 fw-bold text-end">Coupon Discount ({{ $booking->coupon_code }})</td>
                            <td class="px-4 py-2 text-end fw-bold">- ₹{{ number_format($booking->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="bg-light text-primary">
                            <td colspan="2" class="px-4 py-3 fw-bold text-end h5 mb-0">Total Payable / Paid</td>
                            <td class="px-4 py-3 text-end fw-black h5 mb-0">₹{{ number_format($booking->payable_amount ?? $booking->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <!-- Assign Staff -->
        <div class="card mb-4 border-primary border-opacity-25">
            <div class="card-header bg-primary bg-opacity-10 py-3">
                <h5 class="fw-bold mb-0 text-primary">Assign Professional</h5>
            </div>
            <div class="card-body">
                @if($booking->staff)
                    <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                        <div class="avatar-md bg-warning text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px;">
                            {{ substr($booking->staff->name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $booking->staff->name }}</h6>
                            <p class="small text-muted mb-0">{{ $booking->staff->designation }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.custom_bookings.assign', $booking->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Select Staff Member</label>
                        <select name="staff_id" class="form-select select2 rounded-3 py-2" data-placeholder="Choose Staff..." required>
                            <option value=""></option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ ($booking->staff_id == $staff->id) ? 'selected' : '' }}>
                                    {{ $staff->name }} ({{ $staff->designation }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                        {{ $booking->staff_id ? 'Update Assignment' : 'Confirm & Assign' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Booking Status Control</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.custom_bookings.status', $booking->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Override Status</label>
                        <select name="status" class="form-select rounded-3 py-2" required>
                            @foreach(['pending', 'confirmed', 'accepted', 'on_the_way', 'started', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Delete Custom Booking -->
        <form action="{{ route('admin.custom_bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this custom booking?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold small">Delete Custom Booking</button>
        </form>
    </div>
</div>
@endsection
