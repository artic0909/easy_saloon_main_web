@extends('admin.layout.app')

@section('page_title', 'Promo Banner Management')

@section('content')
<div class="row g-4">
    <!-- Promo List -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Active Promo Banners</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @forelse($promos as $promo)
                    <div class="col-md-6">
                        <div class="card shadow-sm border overflow-hidden h-100">
                            @if($promo->image)
                            <img src="{{ asset('storage/' . $promo->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h6 class="fw-bold mb-1 text-dark">{{ $promo->title }}</h6>
                                <p class="text-muted small mb-2">{{ $promo->subtitle }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge {{ $promo->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3">
                                        {{ $promo->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $promo->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form id="delete-form-{{ $promo->id }}" action="{{ route('admin.cms.promo.destroy', $promo->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $promo->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-image text-muted" style="font-family: 'Outfit', sans-serif; font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No promo banners found. Add your first one!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Moved outside to prevent flickering) -->
    @foreach($promos as $promo)
    <div class="modal fade" id="editModal{{ $promo->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-start shadow-2xl rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-black">Edit Promo Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.cms.promo.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control rounded-3" value="{{ $promo->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subtitle</label>
                            <input type="text" name="subtitle" class="form-control rounded-3" value="{{ $promo->subtitle }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Image (Leave blank to keep current)</label>
                            <input type="file" name="image" class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link</label>
                            <input type="text" name="link" class="form-control rounded-3" value="{{ $promo->link }}">
                        </div>
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" {{ $promo->is_active ? 'checked' : '' }} value="1">
                            <label class="form-check-label fw-bold">Is Active</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Update Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Add New Promo Banner</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.cms.promo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Promo Image</label>
                        <input type="file" name="image" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Action Link</label>
                        <input type="text" name="link" class="form-control rounded-3" placeholder="https://...">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" checked value="1">
                        <label class="form-check-label">Is Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-3">Upload Promo</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this promo banner?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
