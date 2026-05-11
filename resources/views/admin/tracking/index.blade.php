@extends('admin.layout.app')

@section('page_title', 'Live Staff Tracking')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 600px; border-radius: 1rem; }
    .staff-card { cursor: pointer; transition: all 0.2s; }
    .staff-card:hover { background-color: #f8f9fa; }
    .staff-card.active { border-left: 4px solid var(--admin-secondary); background-color: #fff9f0; }
</style>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Active Home Services</h5>
            </div>
            <div class="card-body p-0 overflow-auto" style="max-height: 540px;">
                @forelse($activeBookings as $booking)
                <div class="staff-card p-4 border-bottom {{ loop->first ? 'active' : '' }}" onclick="focusOnStaff({{ $booking->id }})">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                            {{ substr($booking->staff->user->name ?? '?', 0, 1) }}
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $booking->staff->user->name ?? 'Unassigned' }}</h6>
                    </div>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-person me-1"></i> Customer: {{ $booking->user->name }}
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge rounded-pill bg-info-subtle text-info text-uppercase px-2" style="font-size: 10px;">{{ str_replace('_', ' ', $booking->status) }}</span>
                        <small class="text-primary fw-bold">#{{ $booking->booking_number }}</small>
                    </div>
                </div>
                @empty
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-geo-alt h1 opacity-25"></i>
                    <p class="mt-2">No active home service bookings found.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([22.5726, 88.3639], 12); // Default to Kolkata or change as needed

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var markers = {};

    @foreach($staffLocations as $location)
        var marker = L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map)
            .bindPopup("<b>{{ $location->staff->user->name }}</b><br>Booking: #{{ $location->booking->booking_number }}<br>Status: {{ $location->booking->status }}");
        markers[{{ $location->booking_id }}] = marker;
    @endforeach

    function focusOnStaff(bookingId) {
        if (markers[bookingId]) {
            var marker = markers[bookingId];
            map.setView(marker.getLatLng(), 15);
            marker.openPopup();
        }
    }
</script>
@endsection
