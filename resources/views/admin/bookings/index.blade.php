@extends('admin.layout.app')

@section('page_title', 'Booking Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0">All Appointments</h5>
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
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $booking->service_type == 'home' ? 'Home Visit' : 'Salon Visit' }}
                            </span>
                        </td>
                        <td>
                            @if($booking->staff)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-xs bg-warning bg-opacity-10 text-warning rounded-circle text-center" style="width: 24px; height: 24px; line-height: 24px; font-size: 10px;">
                                        {{ substr($booking->staff->user->name, 0, 1) }}
                                    </div>
                                    <span class="small fw-bold">{{ $booking->staff->user->name }}</span>
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
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Manage</a>
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
