@extends('admin.layout.app')

@section('page_title', 'Service Catalog')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0">Platform Services</h5>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Create New Service
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Service</th>
                        <th class="py-3 border-0">Category</th>
                        <th class="py-3 border-0">Price</th>
                        <th class="py-3 border-0">Duration</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $service->image ? asset('storage/' . $service->image) : 'https://placehold.co/100x100?text=Service' }}" class="rounded-3 shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                                <div class="fw-bold text-dark">{{ $service->name }}</div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">{{ $service->category->name }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">₹{{ number_format($service->sale_price) }}</div>
                            @if($service->price > $service->sale_price)
                                <small class="text-muted text-decoration-line-through">₹{{ number_format($service->price) }}</small>
                            @endif
                        </td>
                        <td><i class="bi bi-clock me-1"></i> {{ $service->duration_minutes }} min</td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">Active</span>
                        </td>
                        <td class="px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 text-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $services->links() }}
    </div>
</div>
@endsection
