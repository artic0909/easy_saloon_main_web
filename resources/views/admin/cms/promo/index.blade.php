@extends('admin.layout.app')

@section('page_title', 'Promo Banner Management')

@section('styles')
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .btn-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .btn-light-primary { background: #eef2ff; color: #4f46e5; border: none; }
    .btn-light-primary:hover { background: #4f46e5; color: white; }
    .btn-light-danger { background: #fff5f5; color: #ff4d4d; border: none; }
    .btn-light-danger:hover { background: #ff4d4d; color: white; }
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Promo List -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-4 border-0">
                <h5 class="fw-black mb-0">Active Promo Banners</h5>
            </div>
            <div class="card-body px-4 pb-4 pt-0">
                <div class="row">
                    @forelse($promos as $promo)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm border-0 overflow-hidden h-100 rounded-4 hover-lift">
                            <div class="position-relative">
                                @if($promo->image)
                                <img src="{{ asset('storage/' . $promo->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                                @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                                @endif
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge {{ $promo->is_active ? 'bg-success' : 'bg-danger' }} shadow-sm px-3 py-2 rounded-pill">
                                        {{ $promo->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <h6 class="fw-black mb-1 text-dark fs-5">{{ $promo->title }}</h6>
                                <p class="text-muted small mb-4">{{ $promo->subtitle }}</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="small text-muted">
                                        <i class="bi bi-link-45deg"></i> {{ Str::limit($promo->link, 20) ?: 'No link' }}
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $promo->id }}" title="Edit Promo">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form id="delete-form-{{ $promo->id }}" action="{{ route('admin.cms.promo.destroy', $promo->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-icon btn-light-danger" onclick="confirmDelete({{ $promo->id }})" title="Delete Promo">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-image text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h6 class="fw-bold text-muted">No promo banners found</h6>
                        <p class="text-muted small">Add your first promo banner from the sidebar form.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Form -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top rounded-4" style="top: 100px; z-index: 10;">
            <div class="card-header bg-white py-4 border-0">
                <h5 class="fw-black mb-0">Add New Promo</h5>
            </div>
            <div class="card-body p-4 pt-0">
                <form action="{{ route('admin.cms.promo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">TITLE</label>
                        <input type="text" name="title" class="form-control rounded-3 bg-light border-0 py-3 px-4" placeholder="Ex: Best *Beauty* Service" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">SUBTITLE</label>
                        <input type="text" name="subtitle" class="form-control rounded-3 bg-light border-0 py-3 px-4" placeholder="Ex: Limited time offer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PROMO IMAGE</label>
                        <input type="file" name="image" class="form-control rounded-3 bg-light border-0 py-3 px-4" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ACTION LINK</label>
                        <input type="text" name="link" class="form-control rounded-3 bg-light border-0 py-3 px-4" placeholder="https://...">
                    </div>
                    <div class="form-check form-switch mb-4 mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" checked value="1">
                        <label class="form-check-label fw-bold small">Active Status</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Promo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@foreach($promos as $promo)
<div class="modal fade" id="editModal{{ $promo->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start shadow-2xl border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-black">Edit Promo Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.cms.promo.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Title</label>
                        <input type="text" name="title" class="form-control rounded-3 bg-light border-0 py-3 px-4" value="{{ $promo->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control rounded-3 bg-light border-0 py-3 px-4" value="{{ $promo->subtitle }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Image (Optional)</label>
                        <input type="file" name="image" class="form-control rounded-3 bg-light border-0 py-3 px-4">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Link</label>
                        <input type="text" name="link" class="form-control rounded-3 bg-light border-0 py-3 px-4" value="{{ $promo->link }}">
                    </div>
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" {{ $promo->is_active ? 'checked' : '' }} value="1">
                        <label class="form-check-label fw-bold small">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">Update Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
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
