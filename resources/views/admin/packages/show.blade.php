@extends('admin.layout.app')

@section('page_title', 'Package Details')

@section('page_actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-accent shadow-sm">
        <i class="bi bi-pencil-square"></i> Edit Package
    </a>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-light shadow-sm">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Main Package Info -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            @if($package->image)
                <div class="position-relative" style="height: 350px;">
                    <img src="{{ asset('storage/' . $package->image) }}" class="w-100 h-100 object-fit-cover">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.7));"></div>
                    <div class="position-absolute bottom-0 start-0 p-5 text-white">
                        <h1 class="display-4 fw-black mb-0">{{ $package->name }}</h1>
                        <p class="opacity-75 mb-0">Slug: {{ $package->slug }}</p>
                    </div>
                </div>
            @else
                <div class="card-body p-5">
                    <h1 class="display-4 fw-black mb-2">{{ $package->name }}</h1>
                    <p class="text-muted mb-0">Slug: {{ $package->slug }}</p>
                </div>
            @endif

            <div class="card-body p-5">
                <div class="d-flex align-items-center gap-4 mb-5 pb-4 border-bottom">
                    <div class="price-box">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Package Sale Price</p>
                        <h3 class="text-primary fw-black mb-0">₹{{ number_format($package->sale_price) }}</h3>
                    </div>
                    <div class="price-box border-start ps-4">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Original Value</p>
                        <h3 class="text-muted fw-bold mb-0 text-decoration-line-through">₹{{ number_format($package->original_price) }}</h3>
                    </div>
                    <div class="ms-auto text-end">
                        <p class="text-muted small text-uppercase fw-bold mb-1">Status</p>
                        @if($package->is_active)
                            <span class="badge bg-success-subtle px-4 py-2">Active</span>
                        @else
                            <span class="badge bg-danger-subtle px-4 py-2">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="package-details mt-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-info-circle-fill text-primary me-2"></i> About this Package</h5>
                    <div class="text-muted lh-lg">
                        {!! $package->details !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Services Included -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-transparent py-4 px-4">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-scissors text-primary me-2"></i> Included Services</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($package->items as $item)
                        <div class="list-group-item p-4 border-0 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                @if($item->service->image)
                                    <img src="{{ asset('storage/' . $item->service->image) }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-scissors text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-dark mb-1">{{ $item->service->name }}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary-subtle text-primary py-0 px-2" style="font-size: 0.65rem;">{{ $item->service->category->name }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> {{ $item->service->duration_minutes }} min</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.65rem;">₹{{ number_format($item->service->original_price) }}</small>
                                    <small class="fw-bold text-dark">₹{{ number_format($item->service->sale_price) }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                            No services included in this package.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-light border-0 p-4 rounded-bottom-5">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold uppercase">Total Service Value</span>
                    <span class="text-dark fw-bold">₹{{ number_format($package->original_price) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-primary small fw-bold uppercase">You Save</span>
                    <span class="text-success fw-bold">₹{{ number_format($package->original_price - $package->sale_price) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .object-fit-cover { object-fit: cover; }
    .card { border-radius: 2rem !important; }
    .list-group-item:last-child { border-bottom: none !important; }
    .package-details img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 1rem 0;
    }
</style>
@endsection
