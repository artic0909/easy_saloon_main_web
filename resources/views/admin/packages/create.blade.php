@extends('admin.layout.app')

@section('page_title', 'Create Service Package')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">Package Name</label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-3 px-4 rounded-4" placeholder="Enter package name" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark mb-3">Included Services (Categorized)</label>
                            <select name="services[]" class="form-select select2" multiple required>
                                @foreach($services->groupBy('category.name') as $categoryName => $categoryServices)
                                    <optgroup label="{{ $categoryName ?: 'Uncategorized' }}">
                                        @foreach($categoryServices as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->sale_price ?? $service->price }}">
                                                {{ $service->name }} (₹{{ $service->sale_price ?? $service->price }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Original Price (₹)</label>
                            <input type="number" name="original_price" id="original_price" class="form-control bg-light border-0 py-3 px-4 rounded-4" placeholder="0.00" readonly required>
                            <small class="text-muted">Auto-calculated from services</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Sale Price (₹)</label>
                            <input type="number" name="sale_price" class="form-control bg-light border-0 py-3 px-4 rounded-4" placeholder="0.00" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Package Details</label>
                            <textarea name="details" rows="4" class="form-control bg-light border-0 py-3 px-4 rounded-4" placeholder="Describe what's included in this package..." required></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">Package Image</label>
                            <div class="p-4 border-2 border-dashed rounded-4 text-center bg-light">
                                <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                <div class="mt-2 small text-muted">Click to upload or drag and drop</div>
                                <input type="file" name="image" class="form-control mt-3" accept="image/*">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary flex-grow-1 py-3 shadow-sm">
                                    <i class="bi bi-check-circle-fill"></i> Save Package
                                </button>
                                <a href="{{ route('admin.packages.index') }}" class="btn btn-light px-5 py-3">Cancel</a>
                            </div>
                        </div>
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
        $('.select2').on('change', function() {
            let total = 0;
            $(this).find(':selected').each(function() {
                total += parseFloat($(this).data('price')) || 0;
            });
            $('#original_price').val(total.toFixed(2));
        });
    });
</script>
@endsection
