@extends('admin.layout.app')

@section('page_title', 'Service Packages')

@section('page_actions')
<a href="{{ route('admin.packages.create') }}" class="btn btn-primary shadow-sm">
    <i class="bi bi-box-fill"></i> Create New Package
</a>
@endsection

@section('content')
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
                        <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-light shadow-sm" title="Edit Package">
                            <i class="bi bi-pencil-square text-primary"></i>
                        </a>
                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Delete this package?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-light shadow-sm" title="Remove Package">
                                <i class="bi bi-trash3-fill text-danger"></i>
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
