@extends('admin.layout.app')

@section('page_title', isset($service) ? 'Edit Service' : 'Add New Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">{{ isset($service) ? 'Update Service Details' : 'Service Creation' }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($service) ? route('admin.services.update', $service->id) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($service))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Service Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $service->name ?? '') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select select2 rounded-3 py-2 @error('category_id') is-invalid @enderror" data-placeholder="Select Category" required>
                                <option value=""></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $service->category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sub Category</label>
                            <select name="sub_category_id" class="form-select select2 rounded-3 py-2 @error('sub_category_id') is-invalid @enderror" data-placeholder="Select Sub Category">
                                <option value=""></option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ (old('sub_category_id', $service->sub_category_id ?? '') == $subcategory->id) ? 'selected' : '' }}>
                                        {{ $subcategory->category->name }} > {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Equipment Use</label>
                            <select name="equipment_id" class="form-select select2 rounded-3 py-2 @error('equipment_id') is-invalid @enderror" data-placeholder="Select Equipment">
                                <option value=""></option>
                                @foreach($equipment as $item)
                                    <option value="{{ $item->id }}" {{ (old('equipment_id', $service->equipment_id ?? '') == $item->id) ? 'selected' : '' }}>
                                        {{ $item->subCategory->name }} > {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Regular Price (₹)</label>
                            <input type="number" name="original_price" class="form-control rounded-3 py-2 @error('original_price') is-invalid @enderror" value="{{ old('original_price', $service->original_price ?? '') }}" required min="0">
                            @error('original_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sale Price (₹)</label>
                            <input type="number" name="sale_price" class="form-control rounded-3 py-2 @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $service->sale_price ?? '') }}" required min="0">
                            @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Duration (Minutes)</label>
                            <input type="number" name="duration_minutes" class="form-control rounded-3 py-2 @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $service->duration_minutes ?? '') }}" required min="1">
                            @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Details</label>
                            <textarea name="details" class="form-control rounded-3 py-2" rows="4">{{ old('details', $service->details ?? '') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Image</label>
                            @if(isset($service) && $service->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $service->image) }}" class="rounded-3 shadow-sm" style="width: 150px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                {{ isset($service) ? 'Save Service Changes' : 'Create Service' }}
                            </button>
                            <a href="{{ route('admin.services.index') }}" class="btn btn-light rounded-pill px-5 py-2 fw-bold ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
