@extends('admin.layout.app')

@section('page_title', 'Service Details')

@section('page_actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-pencil me-2"></i> Edit Service
    </a>
    <a href="{{ route('admin.services.index') }}" class="btn btn-light border rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i> Back to List
    </a>
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Left Column: Details & Content -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 mb-4">
                    <img src="{{ $service->image ? asset('storage/' . $service->image) : 'https://placehold.co/200x200?text=Service' }}" class="rounded-4 shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">
                            {{ $service->category->name }} &gt; {{ $service->subCategory->name }}
                        </span>
                        <h2 class="fw-black mb-1">{{ $service->name }}</h2>
                        <div class="text-muted small">Created on {{ $service->created_at->format('M d, Y') }}</div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-4 text-center">
                            <div class="text-muted small mb-1">Regular Price</div>
                            <h4 class="fw-bold mb-0 text-dark">₹{{ number_format($service->original_price) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-success bg-opacity-10 rounded-4 text-center">
                            <div class="text-success small mb-1">Sale Price</div>
                            <h4 class="fw-bold mb-0 text-success">₹{{ number_format($service->sale_price) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-4 text-center">
                            <div class="text-warning small mb-1">Duration</div>
                            <h4 class="fw-bold mb-0 text-warning">{{ $service->duration_minutes }} min</h4>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> Service Description</h5>
                    <div class="text-muted lh-lg">
                        {!! $service->details ?? 'No details provided.' !!}
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-3"><i class="bi bi-check2-circle me-2"></i> What's Included?</h5>
                    <div class="row g-2">
                        @if($service->what_included && count($service->what_included) > 0)
                            @foreach($service->what_included as $item)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                        <i class="bi bi-check-lg text-success"></i>
                                        <span class="small fw-medium">{{ $item }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted small ps-3">No specific inclusions listed.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Equipment & Stats -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0">Equipment Used</h6>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="d-flex flex-column gap-3">
                    @forelse($service->equipment as $item)
                        <div class="d-flex align-items-center gap-3 p-3 bg-white border rounded-4 shadow-sm-hover transition">
                            <div class="avatar-sm bg-accent-subtle text-accent rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">{{ $item->name }}</div>
                                <div class="text-muted" style="font-size: 10px;">{{ $item->subCategory->name }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-slash-circle fs-2 text-muted mb-2"></i>
                            <div class="text-muted small">No equipment assigned.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Service Statistics</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-white-50 small">Total Bookings</span>
                    <span class="fw-bold">128</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-white-50 small">Rating</span>
                    <span class="fw-bold text-warning"><i class="bi bi-star-fill me-1"></i> 4.8</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-white-50 small">Revenue</span>
                    <span class="fw-bold text-accent">₹38,400</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-sm-hover:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        transform: translateY(-2px);
    }
    .transition {
        transition: all 0.3s ease;
    }
    .bg-accent-subtle { background: rgba(198, 166, 100, 0.1); }
    .text-accent { color: #c6a664; }
</style>
@endsection
