@extends('admin.layout.app')

@section('page_title', 'Add New Sub Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Sub Category Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.subcategories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Parent Category</label>
                        <select name="category_id" class="form-select select2 rounded-3" data-placeholder="Select Category" required>
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Sub Category Name</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Save Sub Category</button>
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-light rounded-pill px-5">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
