@extends('admin.layout.app')

@section('page_title', 'Booking Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">All Appointments</h5>
        </div>
        
        <!-- Filters Area -->
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Search Booking</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Number, Customer or Staff..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Filter Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Show Rows</label>
                <select name="per_page" class="form-select">
                    @foreach([10, 20, 50, 100] as $num)
                        <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>{{ $num }} Rows</option>
                    @endforeach
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Show All</option>
                </select>
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Booking #</th>
                        <th class="py-3 border-0">Customer</th>
                        <th class="py-3 border-0">Date & Slot</th>
                        <th class="py-3 border-0">Service Type</th>
                        <th class="py-3 border-0">Assigned Staff</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td class="px-4">
                            <span class="fw-bold text-dark">#{{ $booking->booking_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $booking->user->name }}</div>
                            <div class="small text-muted">{{ $booking->user->phone }}</div>
                        </td>
                        <td>
                            <div class="fw-medium">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</div>
                            <div class="small text-muted text-uppercase tracking-widest">{{ $booking->time_slot }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border px-2 py-1">
                                {{ $booking->type }}
                            </span>
                        </td>
                        <td>
                            @if($booking->staff)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-xs bg-warning bg-opacity-10 text-warning rounded-circle text-center" style="width: 24px; height: 24px; line-height: 24px; font-size: 10px;">
                                        {{ substr($booking->staff->name, 0, 1) }}
                                    </div>
                                    <span class="small fw-bold">{{ $booking->staff->name }}</span>
                                </div>
                            @else
                                <span class="text-danger small fw-bold">Not Assigned</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill 
                                @if($booking->status == 'completed') bg-success-subtle text-success
                                @elseif($booking->status == 'pending') bg-warning-subtle text-warning
                                @elseif($booking->status == 'cancelled') bg-danger-subtle text-danger
                                @else bg-primary-subtle text-primary @endif px-3 py-2">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 text-end">
                            @if($booking instanceof \App\Models\CustomBooking)
                                <a href="{{ route('admin.custom_bookings.show', $booking->id) }}" class="btn btn-sm btn-info text-white rounded-pill px-3">Manage Custom</a>
                            @else
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Manage</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
