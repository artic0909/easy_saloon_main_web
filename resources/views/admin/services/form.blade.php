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
                            <select name="category_id" id="category" class="form-select select2 rounded-3 py-2 @error('category_id') is-invalid @enderror" data-placeholder="Select Category" required>
                                <option value=""></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $service->category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sub Category</label>
                            <select name="sub_category_id" id="sub_category" class="form-select select2 rounded-3 py-2 @error('sub_category_id') is-invalid @enderror" data-placeholder="Select Sub Category">
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
                            <select name="equipment[]" id="equipment" class="form-select select2 rounded-3 py-2 @error('equipment') is-invalid @enderror" data-placeholder="Select Equipment" multiple>
                                <option value=""></option>
                                @foreach($equipment as $item)
                                    <option value="{{ $item->id }}" {{ (isset($service) && $service->equipment->contains($item->id)) ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                            <label class="form-label fw-bold">What Included?</label>
                            <div id="what_included_container">
                                @php
                                    $what_included = old('what_included', $service->what_included ?? []);
                                    if (empty($what_included)) $what_included = [''];
                                @endphp
                                @foreach($what_included as $item)
                                    <div class="input-group mb-2 what-included-item">
                                        <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included..." value="{{ $item }}">
                                        <button type="button" class="btn btn-outline-danger remove-field border">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-success add-field border">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Details</label>
                            <textarea name="details" id="details" class="form-control summernote rounded-3 py-2" rows="4">{{ old('details', $service->details ?? '') }}</textarea>
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
@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Summernote
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
            ],
            callbacks: {
                onInit: function() {
                    $(this).next('.note-editor').addClass('rounded-4 overflow-hidden border-1');
                }
            }
        });

        // Dependent Dropdowns
        $('#category').on('change', function() {
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: '/admin/get-subcategories/' + category_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#sub_category').empty();
                        $('#sub_category').append('<option value=""></option>');
                        $.each(data, function(key, value) {
                            $('#sub_category').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#sub_category').trigger('change');
                    }
                });
            } else {
                $('#sub_category').empty();
                $('#sub_category').append('<option value=""></option>');
                $('#sub_category').trigger('change');
            }
        });

        $('#sub_category').on('change', function() {
            var subcategory_id = $(this).val();
            if (subcategory_id) {
                $.ajax({
                    url: '/admin/get-equipment/' + subcategory_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#equipment').empty();
                        $.each(data, function(key, value) {
                            $('#equipment').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#equipment').trigger('change');
                    }
                });
            } else {
                $('#equipment').empty();
                $('#equipment').trigger('change');
            }
        });

        // Dynamic "What Included" fields
        $(document).on('click', '.add-field', function() {
            var html = `
                <div class="input-group mb-2 what-included-item">
                    <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included...">
                    <button type="button" class="btn btn-outline-danger remove-field border">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success add-field border">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>`;
            $('#what_included_container').append(html);
        });

        $(document).on('click', '.remove-field', function() {
            if ($('.what-included-item').length > 1) {
                $(this).closest('.what-included-item').remove();
            } else {
                $(this).closest('.what-included-item').find('input').val('');
            }
        });
    });
</script>
@endsection
@endsection
