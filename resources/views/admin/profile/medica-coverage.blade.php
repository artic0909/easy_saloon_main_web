@extends('admin.layout.app')

@section('page_title', 'Media Coverage')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Existing Media Stories</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach($blogs as $blog)
                    <div class="col-md-6">
                        <div class="card shadow-sm border h-100">
                            <img src="{{ asset('storage/' . $blog->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="fw-bold text-dark">{{ $blog->title }}</h6>
                                <div class="text-muted small mb-3">
                                    {!! Str::limit($blog->description, 100) !!}
                                </div>
                                <div class="d-flex justify-content-end">
                                    <form id="delete-form-{{ $blog->id }}" action="{{ route('admin.profile.media_coverage.destroy', $blog->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $blog->id }})">
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

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Add Media News</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.media_coverage.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Source / Title</label>
                        <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. Times of India" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thumbnail Image</label>
                        <input type="file" name="image" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Story Description</label>
                        <textarea name="description" class="form-control rounded-4 summernote" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-3">Publish Story</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection
