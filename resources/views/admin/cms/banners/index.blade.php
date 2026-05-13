@extends('admin.layout.app')

@section('page_title', 'Homepage Banner Management')

@section('content')
<div class="row g-4">
    <!-- Banner List -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Active Banners</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach($banners as $banner)
                    <div class="col-md-6">
                        <div class="card shadow-sm border overflow-hidden h-100">
                            <img src="{{ asset('storage/' . $banner->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $banner->title }}</h6>
                                    <span class="badge bg-primary rounded-pill">Order: {{ $banner->sort_order }}</span>
                                </div>
                                <p class="text-muted small mb-3">{{ $banner->subtitle ?? 'No subtitle' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge {{ $banner->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3">
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <form id="delete-form-{{ $banner->id }}" action="{{ route('admin.cms.banners.destroy', $banner->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $banner->id }})" title="Delete Banner">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Add Banner Form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Add New Banner</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.cms.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Banner Title</label>
                        <input type="text" name="title" class="form-control rounded-3" required>
                        <small class="text-muted">Use *text* for bold & golden type color and | for new line</small>
                        <br>
                        <small class="text-muted">Ex: Best *Beauty* Service | For *Women*</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subtitle / Description</label>
                        <input type="text" name="subtitle" class="form-control rounded-3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Banner Image</label>
                        <input type="file" name="image" class="form-control rounded-3" required>
                        <small class="text-muted">Recommended: 1920x600px</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Action Link</label>
                        <input type="text" name="link" class="form-control rounded-3" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Order</label>
                        <input type="number" name="sort_order" class="form-control rounded-3" value="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-3">Upload Banner</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
