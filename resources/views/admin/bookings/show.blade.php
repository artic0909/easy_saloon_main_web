@extends('admin.layout.app')

@section('page_title', 'Manage Booking #' . $booking->booking_number)

@section('content')
<div class="row g-4">
    <!-- Booking Details -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Appointment Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Customer Details</label>
                        <div class="mt-2 p-3 bg-light rounded-3">
                            <h6 class="fw-bold mb-1">{{ $booking->user->name }}</h6>
                            <p class="text-muted mb-1"><i class="bi bi-envelope me-2"></i>{{ $booking->user->email }}</p>
                            <p class="text-muted mb-0"><i class="bi bi-telephone me-2"></i>{{ $booking->user->phone }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Service Schedule</label>
                        <div class="mt-2 p-3 bg-light rounded-3">
                            <h6 class="fw-bold mb-1"><i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}</h6>
                            <p class="text-muted mb-0"><i class="bi bi-clock me-2 text-uppercase"></i>{{ $booking->time_slot }} Slot</p>
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

                    @php
                        $equipments = $booking->equipment;
                        if (is_string($equipments)) {
                            $equipments = json_decode($equipments, true);
                        }
                    @endphp

                    @if($equipments && count($equipments) > 0)
                    <div class="col-12">
                        <label class="small text-muted text-uppercase fw-bold tracking-widest">Equipments Required</label>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($equipments as $item)
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
                <h5 class="fw-bold mb-0">Services Summary</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-2">Item</th>
                            <th class="py-2">Type</th>
                            <th class="px-4 py-2 text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->items as $item)
                        <tr class="border-bottom">
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $item->package_id ? $item->package->name : $item->service->name }}</div>
                                @if($item->service && $item->service->subCategory && $item->service->subCategory->equipment->count() > 0)
                                    <div class="mt-1">
                                        <small class="text-muted">Required Tools: </small>
                                        @foreach($item->service->subCategory->equipment as $eq)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 py-0 px-2" style="font-size: 0.65rem;">{{ $eq->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ ucfirst($item->item_type) }}</td>
                            <td class="px-4 text-end fw-bold">
                                <div class="d-flex flex-column align-items-end">
                                    @if($item->item_type == 'package' && $item->package)
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-dark">₹{{ number_format($item->price) }}</span>
                                            <small class="text-muted text-decoration-line-through fw-normal" style="font-size: 0.75rem;">₹{{ number_format($item->package->original_price) }}</small>
                                        </div>
                                        <small class="text-success" style="font-size: 0.65rem;">Package Bundle</small>
                                    @elseif($item->item_type == 'service' && $item->service)
                                        @if($item->price > 0)
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-dark">₹{{ number_format($item->price) }}</span>
                                                <small class="text-muted text-decoration-line-through fw-normal" style="font-size: 0.75rem;">₹{{ number_format($item->service->original_price) }}</small>
                                            </div>
                                        @else
                                            <span class="text-success small fw-normal mb-1">Included</span>
                                            <div class="flex items-baseline gap-2 opacity-50">
                                                <small class="fw-bold" style="font-size: 0.7rem;">₹{{ number_format($item->service->sale_price) }}</small>
                                                <small class="text-decoration-line-through" style="font-size: 0.65rem;">₹{{ number_format($item->service->original_price) }}</small>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="2" class="px-4 py-3 fw-bold text-end">Total Amount Paid</td>
                            <td class="px-4 py-3 text-end fw-black h5 mb-0 text-primary">₹{{ number_format($booking->payable_amount) }}</td>
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

                <form action="{{ route('admin.bookings.assign', $booking->id) }}" method="POST">
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
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Booking Status Control</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST">
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
    </div>
</div>
@endsection
