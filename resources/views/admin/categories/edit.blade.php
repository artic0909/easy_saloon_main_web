@extends('admin.layout.app')

@section('page_title', 'Edit Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Update Category</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-bold">Category Name</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Category Image</label>
                        @if($category->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="rounded-3 shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                                <div class="text-muted small mt-1">Current Image</div>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text small">Upload new image to replace current one. Recommended: 500x500px</div>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Update Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light rounded-pill px-5">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
