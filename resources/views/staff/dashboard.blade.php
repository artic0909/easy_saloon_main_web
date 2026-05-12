@extends('admin.layout.app')

@section('page_title', 'Staff Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-0">
            <div class="card-body p-4">
                <div class="stat-icon" style="background: rgba(198, 166, 100, 0.1);">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_bookings'] }}</h3>
                <p class="text-muted small mb-0">Total Bookings</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0">
            <div class="card-body p-4">
                <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['pending_bookings'] }}</h3>
                <p class="text-muted small mb-0">Pending Tasks</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0">
            <div class="card-body p-4">
                <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['completed_bookings'] }}</h3>
                <p class="text-muted small mb-0">Completed</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0">
            <div class="card-body p-4">
                <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['today_bookings'] }}</h3>
                <p class="text-muted small mb-0">Today's Schedule</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Today's Schedule</h5>
                <span class="badge bg-light text-dark border">{{ date('M d, Y') }}</span>
            </div>
            <div class="card-body">
                @forelse($today_bookings as $booking)
                <div class="d-flex align-items-center gap-4 p-3 rounded-4 bg-light mb-3 border border-white">
                    <div class="text-center px-3 border-end">
                        <div class="fw-bold text-primary">{{ $booking->time_slot }}</div>
                        <div class="small text-muted uppercase" style="font-size: 10px;">Slot</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">
                            {{ $booking->user->name }} 
                            <span class="badge bg-primary-subtle text-primary border ms-2" style="font-size: 9px; font-weight: 500;">{{ $booking->type }}</span>
                        </div>
                        <div class="text-muted small">
                            @foreach($booking->items as $item)
                                {{ $item->service->name ?? 'Deleted Service' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>
                    <div>
                        @if(!$booking->staff_id)
                            <span class="badge bg-danger-subtle text-danger">Unassigned</span>
                        @else
                            <span class="badge {{ $booking->status == 'Completed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                {{ $booking->status }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-light btn-sm rounded-pill px-3">Details</a>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="display-6 text-muted mb-3 opacity-25"><i class="bi bi-calendar-x"></i></div>
                    <p class="text-muted">No bookings assigned for today.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Quick Profile</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-md bg-accent-subtle rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold fs-3 text-accent mb-3" style="width: 80px; height: 80px; background: rgba(198, 166, 100, 0.1);">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <h6 class="fw-bold mb-1">{{ auth()->user()->name }}</h6>
                    <p class="text-muted small mb-3">{{ auth()->user()->staffProfile->designation ?? 'Service Professional' }}</p>
                    
                    <form action="{{ route('staff.profile.availability') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch d-inline-block p-0">
                            <label class="form-check-label fw-bold small me-5" for="availabilitySwitch">Available for Bookings</label>
                            <input class="form-check-input" type="checkbox" name="is_available" id="availabilitySwitch" 
                                {{ auth()->user()->staffProfile && auth()->user()->staffProfile->is_available ? 'checked' : '' }}
                                onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
                <hr class="opacity-50">
                <div class="mt-4">
                    <h6 class="fw-bold small text-muted uppercase mb-3">Recent Notifications</h6>
                    <div class="small text-muted italic">No new notifications.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
