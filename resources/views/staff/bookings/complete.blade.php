@extends('admin.layout.app')

@section('page_title', 'My Completed Bookings')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-4 px-4 border-0">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">Completed Bookings</h5>
        </div>
        
        <!-- Filters Area -->
        <form action="{{ route('staff.bookings.completed') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
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
                    <a href="{{ route('staff.bookings.completed') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                        <th>Payment</th>
                        <th>Rating</th>
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
                                @if($booking instanceof \App\Models\CustomBooking)
                                    @foreach($booking->services as $service)
                                        {{ $service->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                @else
                                    @foreach($booking->items as $item)
                                        {{ $item->service->name ?? 'Deleted Service' }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($booking->is_paid)
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i> Paid
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">
                                    <i class="bi bi-clock-history me-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($booking->rating)
                                <div class="text-warning small text-nowrap">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $booking->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success px-3">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            @if($booking instanceof \App\Models\CustomBooking)
                                <a href="{{ route('staff.custom_bookings.show', $booking->id) }}" class="btn btn-action btn-view" title="View Custom Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('staff.bookings.show', $booking->id) }}" class="btn btn-action btn-view" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="display-6 text-muted mb-3 opacity-25"><i class="bi bi-patch-check-fill"></i></div>
                            <p class="text-muted">No completed bookings found in your record.</p>
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
