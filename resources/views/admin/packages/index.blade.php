@extends('admin.layout.app')

@section('page_title', 'Service Packages')

@section('page_actions')
<a href="{{ route('admin.packages.create') }}" class="btn btn-primary shadow-sm">
    <i class="bi bi-box-fill"></i> Create New Package
</a>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.packages.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-muted">Search Packages</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Name or details..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Package Details</th>
                <th>Pricing Structure</th>
                <th>Included Items</th>
                <th class="text-center">Active Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packages as $package)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" class="avatar-md shadow-sm" style="object-fit: cover;">
                        @else
                            <div class="avatar-md bg-light text-muted d-flex align-items-center justify-content-center">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                        <div>
                            <div class="fw-bold text-dark fs-6">{{ $package->name }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $package->details }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="fw-bold text-success">₹{{ number_format($package->sale_price) }}</div>
                    <div class="text-muted text-decoration-line-through" style="font-size: 11px;">₹{{ number_format($package->original_price) }}</div>
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($package->items as $item)
                            <span class="badge bg-primary-subtle text-primary" style="font-size: 9px;">{{ $item->service->name }}</span>
                        @endforeach
                    </div>
                </td>
                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input shadow-none" type="checkbox" {{ $package->is_active ? 'checked' : '' }} style="cursor: pointer; width: 40px; height: 20px;">
                    </div>
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn-action btn-edit" title="Edit Package">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form id="delete-form-{{ $package->id }}" action="{{ route('admin.packages.destroy', $package->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $package->id }})" title="Remove Package">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $packages->links() }}
</div>
@endsection
