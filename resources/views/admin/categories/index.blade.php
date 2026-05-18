@extends('admin.layout.app')

@section('page_title', 'Category Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">All Categories</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i> Add Category
            </a>
        </div>

        <!-- Filters Area -->
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-muted">Search Category</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Category name..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Show Rows</label>
                <select name="per_page" class="form-select">
                    @foreach([10, 20, 50, 100] as $num)
                        <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>{{ $num }} Rows</option>
                    @endforeach
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Show All</option>
                </select>
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">SL</th>
                        <th class="py-3 border-0">Image</th>
                        <th class="py-3 border-0">Name</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr data-bs-toggle="collapse" data-bs-target="#category-{{ $category->id }}" class="cursor-pointer" style="cursor: pointer;">
                        <td class="px-4">
                            <i class="bi bi-chevron-down text-muted me-2"></i>
                            @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $category->name }}</div>
                            <div class="small text-muted">Slug: {{ $category->slug }}</div>
                        </td>
                        <td class="px-4 text-end" onclick="event.stopPropagation()">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-action btn-edit" title="Edit Category">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $category->id }})" title="Remove Category">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <div class="dropdown">
                                    <button class="btn-action btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="openAddServiceModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                                <i class="bi bi-plus-circle me-2"></i> Add Service
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr id="category-{{ $category->id }}" class="collapse bg-light">
                        <td colspan="4" class="p-4 border-0">
                            <div class="card shadow-sm border-0 mb-0">
                                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">Services in {{ $category->name }}</h6>
                                    <button class="btn btn-sm btn-primary rounded-pill" onclick="openAddServiceModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                        <i class="bi bi-plus-lg me-1"></i> Add Service
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Image</th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Duration</th>
                                                <th class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($category->services as $service)
                                            <tr>
                                                <td class="ps-4">
                                                    @if(!empty($service->images))
                                                        <img src="{{ asset('storage/' . $service->images[0]) }}" class="rounded-3 shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $service->name }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">₹{{ number_format($service->sale_price) }}</div>
                                                    @if($service->original_price > $service->sale_price)
                                                        <small class="text-muted text-decoration-line-through">₹{{ number_format($service->original_price) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $service->duration_minutes }} min</td>
                                                <td class="text-end pe-4">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn-action btn-view" onclick='openViewServiceModal(@json($service))' title="View Service">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn-action btn-edit" onclick='openEditServiceModal(@json($service))' title="Edit Service">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form id="delete-service-form-{{ $service->id }}" action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $service->id }}', 'delete-service-form-{{ $service->id }}')" title="Delete Service">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No services found in this category.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $categories->links() }}
    </div>
</div>

<!-- Service Form Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalTitle">Service Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="serviceForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="serviceMethod" value="POST">
                    <input type="hidden" name="service_id" id="service_id" value="">
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Service Name</label>
                            <input type="text" name="name" id="service_name" class="form-control rounded-3 py-2" required>
                        </div>

                        <div class="d-none">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" id="service_category_id" class="form-select rounded-3 py-2" required>
                                <option value=""></option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Equipment Use</label>
                            <select name="equipment[]" id="service_equipment" class="select2 rounded-3" data-placeholder="Select Equipment..." multiple style="width: 100%;">
                                @foreach($equipments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Regular Price (₹)</label>
                            <input type="number" name="original_price" id="service_original_price" class="form-control rounded-3 py-2" required min="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sale Price (₹)</label>
                            <input type="number" name="sale_price" id="service_sale_price" class="form-control rounded-3 py-2" required min="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Duration (Min)</label>
                            <input type="number" name="duration_minutes" id="service_duration_minutes" class="form-control rounded-3 py-2" required min="1">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">What Included?</label>
                            <div id="what_included_container">
                                <div class="input-group mb-2 what-included-item">
                                    <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included...">
                                    <button type="button" class="btn btn-outline-danger remove-field border">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success add-field border">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Details</label>
                            <textarea name="details" id="service_details" class="form-control summernote rounded-3 py-2" rows="4"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Images</label>
                            <div class="mb-3 d-flex flex-wrap gap-2" id="service_images_preview"></div>
                            <input type="file" name="images[]" class="form-control rounded-3 py-2" multiple accept="image/*">
                            <small class="text-muted">You can select multiple images.</small>
                        </div>
                        
                        <div class="col-12 mt-4 text-end">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-light rounded-pill px-4 py-2 fw-bold me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                Save Service
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Service View Modal -->
<div class="modal fade" id="viewServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Service Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div id="view_service_images_carousel" class="d-flex gap-2 overflow-auto pb-2" style="max-width: 300px;">
                        <img id="view_service_image_main" src="" class="rounded-4 shadow-sm" style="width: 120px; height: 120px; object-fit: cover; flex-shrink: 0;">
                    </div>
                    <div>
                        <h3 class="fw-black mb-1" id="view_service_name"></h3>
                        <div class="text-muted mb-2" id="view_service_category"></div>
                        <div class="d-flex gap-3">
                            <div class="badge bg-light text-dark border py-2 px-3">
                                <i class="bi bi-clock me-1"></i> <span id="view_service_duration"></span> min
                            </div>
                            <div class="badge bg-success-subtle text-success py-2 px-3">
                                Sale: ₹<span id="view_service_sale_price"></span>
                            </div>
                            <div class="badge bg-light text-muted border py-2 px-3 text-decoration-line-through">
                                Regular: ₹<span id="view_service_original_price"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold border-bottom pb-2">Description</h6>
                    <div id="view_service_details" class="text-muted lh-lg"></div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold border-bottom pb-2">What's Included</h6>
                        <ul class="list-unstyled mb-0" id="view_service_included"></ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold border-bottom pb-2">Equipment Used</h6>
                        <ul class="list-unstyled mb-0" id="view_service_equipment"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 in Modal
        $('#service_equipment').select2({
            dropdownParent: $('#serviceModal'),
            placeholder: 'Select Equipment',
            allowClear: true
        });

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
            ]
        });

        // Dynamic "What Included" fields
        $(document).on('click', '.add-field', function() {
            var html = `
                <div class="input-group mb-2 what-included-item">
                    <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included...">
                    <button type="button" class="btn btn-outline-danger remove-field border"><i class="bi bi-x-lg"></i></button>
                    <button type="button" class="btn btn-outline-success add-field border"><i class="bi bi-plus-lg"></i></button>
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

    function openAddServiceModal(categoryId, categoryName) {
        $('#serviceForm').attr('action', '{{ route('admin.services.store') }}');
        $('#serviceMethod').val('POST');
        $('#service_id').val('');
        $('#serviceModalTitle').text('Add New Service in ' + categoryName);
        
        // Clear form
        $('#serviceForm')[0].reset();
        $('#service_category_id').val(categoryId);
        $('#service_equipment').val(null).trigger('change');
        $('#service_details').summernote('code', '');
        $('#what_included_container').html(`
            <div class="input-group mb-2 what-included-item">
                <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included...">
                <button type="button" class="btn btn-outline-danger remove-field border"><i class="bi bi-x-lg"></i></button>
                <button type="button" class="btn btn-outline-success add-field border"><i class="bi bi-plus-lg"></i></button>
            </div>
        `);
        $('#service_images_preview').empty();
        
        $('#serviceModal').modal('show');
    }

    function openEditServiceModal(service) {
        $('#serviceForm').attr('action', '/admin/services/' + service.id);
        $('#serviceMethod').val('PUT');
        $('#service_id').val(service.id);
        $('#serviceModalTitle').text('Edit Service');
        
        $('#service_name').val(service.name);
        $('#service_category_id').val(service.category_id);
        $('#service_original_price').val(service.original_price);
        $('#service_sale_price').val(service.sale_price);
        $('#service_duration_minutes').val(service.duration_minutes);
        
        // Equipments
        let equipmentIds = service.equipment ? service.equipment.map(e => e.id) : [];
        $('#service_equipment').val(equipmentIds).trigger('change');
        
        // What Included
        $('#what_included_container').empty();
        if (service.what_included && service.what_included.length > 0) {
            service.what_included.forEach(function(item) {
                $('#what_included_container').append(`
                    <div class="input-group mb-2 what-included-item">
                        <input type="text" name="what_included[]" class="form-control rounded-start-3" value="${item}">
                        <button type="button" class="btn btn-outline-danger remove-field border"><i class="bi bi-x-lg"></i></button>
                        <button type="button" class="btn btn-outline-success add-field border"><i class="bi bi-plus-lg"></i></button>
                    </div>
                `);
            });
        } else {
            $('#what_included_container').html(`
                <div class="input-group mb-2 what-included-item">
                    <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included...">
                    <button type="button" class="btn btn-outline-danger remove-field border"><i class="bi bi-x-lg"></i></button>
                    <button type="button" class="btn btn-outline-success add-field border"><i class="bi bi-plus-lg"></i></button>
                </div>
            `);
        }
        
        // Details
        $('#service_details').summernote('code', service.details || '');
        
        // Images
        $('#service_images_preview').empty();
        if (service.images && service.images.length > 0) {
            service.images.forEach(function(img) {
                $('#service_images_preview').append(`
                    <div class="position-relative d-inline-block">
                        <img src="/storage/${img}" class="rounded-3 shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                        <input type="hidden" name="existing_images[]" value="${img}">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle p-1" style="transform: translate(30%, -30%); line-height: 1;" onclick="this.parentElement.remove()">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `);
            });
        }
        
        $('#serviceModal').modal('show');
    }

    function openViewServiceModal(service) {
        $('#view_service_name').text(service.name);
        $('#view_service_category').text(service.category ? service.category.name : 'No Category');
        $('#view_service_duration').text(service.duration_minutes);
        $('#view_service_sale_price').text(service.sale_price);
        $('#view_service_original_price').text(service.original_price);
        
        if (service.images && service.images.length > 0) {
            $('#view_service_images_carousel').empty();
            service.images.forEach(function(img) {
                $('#view_service_images_carousel').append(`
                    <img src="/storage/${img}" class="rounded-4 shadow-sm" style="width: 120px; height: 120px; object-fit: cover; flex-shrink: 0;">
                `);
            });
        } else {
            $('#view_service_images_carousel').html(`<img src="https://placehold.co/120x120?text=No+Image" class="rounded-4 shadow-sm" style="width: 120px; height: 120px; object-fit: cover; flex-shrink: 0;">`);
        }

        $('#view_service_details').html(service.details || 'No description provided.');

        let includedHtml = '';
        if (service.what_included && service.what_included.length > 0) {
            service.what_included.forEach(function(item) {
                includedHtml += `<li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>${item}</li>`;
            });
        } else {
            includedHtml = '<li class="text-muted">Not specified</li>';
        }
        $('#view_service_included').html(includedHtml);

        let equipmentHtml = '';
        if (service.equipment && service.equipment.length > 0) {
            service.equipment.forEach(function(item) {
                equipmentHtml += `<li class="mb-2"><i class="bi bi-tools text-muted me-2"></i>${item.name}</li>`;
            });
        } else {
            equipmentHtml = '<li class="text-muted">No equipment assigned</li>';
        }
        $('#view_service_equipment').html(equipmentHtml);

        $('#viewServiceModal').modal('show');
    }

    @if($errors->any() && old('category_id'))
        // Auto-reopen modal if there are validation errors on service form submission
        $(document).ready(function() {
            let isEdit = '{{ old("_method") }}' === 'PUT';
            let actionUrl = isEdit ? '/admin/services/{{ old("service_id") }}' : '{{ route("admin.services.store") }}';
            
            $('#serviceForm').attr('action', actionUrl);
            $('#serviceMethod').val('{{ old("_method") }}');
            $('#serviceModalTitle').text(isEdit ? 'Edit Service' : 'Add New Service');
            
            $('#service_name').val(@json(old("name")));
            $('#service_category_id').val('{{ old("category_id") }}');
            
            let oldEquipments = @json(old('equipment', []));
            $('#service_equipment').val(oldEquipments).trigger('change');
            
            $('#service_original_price').val('{{ old("original_price") }}');
            $('#service_sale_price').val('{{ old("sale_price") }}');
            $('#service_duration_minutes').val('{{ old("duration_minutes") }}');
            
            $('#service_details').summernote('code', @json(old('details', '')));
            
            // what included
            let oldIncluded = @json(old('what_included', ['']));
            $('#what_included_container').empty();
            if(oldIncluded.length > 0 && oldIncluded[0] !== '') {
                oldIncluded.forEach(function(item) {
                    $('#what_included_container').append(`
                        <div class="input-group mb-2 what-included-item">
                            <input type="text" name="what_included[]" class="form-control rounded-start-3" value="${item.replace(/"/g, '&quot;')}">
                            <button type="button" class="btn btn-outline-danger remove-field border"><i class="bi bi-x-lg"></i></button>
                            <button type="button" class="btn btn-outline-success add-field border"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    `);
                });
            } else {
                $('#what_included_container').html(`
                    <div class="input-group mb-2 what-included-item">
                        <input type="text" name="what_included[]" class="form-control rounded-start-3" placeholder="Enter what is included...">
                        <button type="button" class="btn btn-outline-danger remove-field border"><i class="bi bi-x-lg"></i></button>
                        <button type="button" class="btn btn-outline-success add-field border"><i class="bi bi-plus-lg"></i></button>
                    </div>
                `);
            }

            // Since it's a validation error, we don't reload the image preview, we just hide it (or we could show the old image if we had it, but standard forms just ask to re-upload or keep existing if edit)
            $('#service_images_preview').empty();
            
            $('#serviceModal').modal('show');
            
            Swal.fire({
                title: 'Validation Error',
                html: '<ul class="text-start mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                icon: 'error'
            });
        });
    @endif
</script>
@endsection
@endsection
