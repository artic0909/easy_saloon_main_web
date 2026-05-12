@extends('admin.layout.app')

@section('page_title', 'Add New Equipment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Equipment Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.equipment_uses.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Sub Category</label>
                        <select name="sub_category_id" class="form-select select2 rounded-3" required>
                            <option value="">Select Sub Category</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ old('sub_category_id') == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->category->name }} > {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Equipment Name</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Save Equipment</button>
                        <a href="{{ route('admin.equipment_uses.index') }}" class="btn btn-light rounded-pill px-5">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
