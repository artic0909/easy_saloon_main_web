@extends('admin.layout.app')

@section('page_title', 'My Assigned Bookings')

@section('content')
<div class="card border-0">
    <div class="card-header bg-white py-4 px-4 border-0">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">Bookings List</h5>
        </div>
        
        <!-- Filters Area -->
        <form action="{{ route('staff.bookings.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Search Booking</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Number, Customer Name or Phone..." value="{{ request('search') }}">
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
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="On the way" {{ request('status') == 'On the way' ? 'selected' : '' }}>On the way</option>
                    <option value="Started" {{ request('status') == 'Started' ? 'selected' : '' }}>Started</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Rows</label>
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
                    <a href="{{ route('staff.bookings.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">SL</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Date & Time</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $key => $booking)
                    <tr>
                        <td class="ps-4">
                            @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </td>
                        <td>
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
                                        <span class="badge bg-primary-subtle text-primary border ms-2" style="font-size: 8px; font-weight: 500;">{{ $booking->type }}</span>
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
                                    @else bg-danger-subtle text-danger @endif px-3">
                                    {{ $booking->status }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-action btn-view" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="display-6 text-muted mb-3 opacity-25"><i class="bi bi-calendar-x"></i></div>
                            <p class="text-muted">No bookings found in your record.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
    <div class="card-footer bg-white border-0 py-4">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
