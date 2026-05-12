@extends('admin.layout.app')

@section('page_title', 'My Assigned Bookings')

@section('content')
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Booking ID</th>
                        <th>Customer</th>
                        <th>Date & Time</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark">#{{ $booking->booking_number }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold">
                                    {{ substr($booking->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">
                                        {{ $booking->user->name }}
                                        <span class="badge bg-light text-muted border ms-2" style="font-size: 8px; font-weight: 500;">{{ ucfirst($booking->service_type) }}</span>
                                    </div>
                                    <div class="text-muted small">{{ $booking->user->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ date('M d, Y', strtotime($booking->booking_date)) }}</div>
                            <div class="text-muted small">{{ $booking->time_slot }}</div>
                        </td>
                        <td>
                            <div class="text-dark small">
                                @foreach($booking->items as $item)
                                    {{ $item->service->name ?? 'Deleted Service' }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if(!$booking->staff_id)
                                <span class="badge bg-danger-subtle text-danger"><i class="bi bi-broadcast me-1"></i> Open for Acceptance</span>
                            @else
                                <span class="badge 
                                    @if($booking->status == 'Completed') bg-success-subtle text-success
                                    @elseif($booking->status == 'Pending') bg-warning-subtle text-warning
                                    @elseif($booking->status == 'Accepted') bg-primary-subtle text-primary
                                    @elseif($booking->status == 'On the way') bg-info-subtle text-info
                                    @elseif($booking->status == 'Started') bg-indigo-subtle text-indigo
                                    @else bg-danger-subtle text-danger @endif">
                                    {{ $booking->status }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-light btn-sm rounded-pill px-4">
                                <i class="bi bi-eye me-1"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="display-6 text-muted mb-3 opacity-25"><i class="bi bi-calendar-x"></i></div>
                            <p class="text-muted">No bookings found in your record.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bookings->hasPages())
    <div class="card-footer bg-white border-0 py-4">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
