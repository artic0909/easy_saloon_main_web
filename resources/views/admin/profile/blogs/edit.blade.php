@extends('admin.layout.app')

@section('page_title', 'Edit Media Story')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Update Story Information</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.media_coverage.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-bold">Source / Headline</label>
                        <input type="text" name="title" class="form-control rounded-3" value="{{ $blog->title }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Thumbnail Image</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $blog->image) }}" class="rounded-3 border" style="width: 150px;">
                        </div>
                        <input type="file" name="image" class="form-control rounded-3">
                        <small class="text-muted">Leave blank to keep current image</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Story Description</label>
                        <textarea name="description" class="form-control rounded-4 summernote" rows="8" required>{{ $blog->description }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.profile.media_coverage') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill">Update Story</button>
                    </div>
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
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection
