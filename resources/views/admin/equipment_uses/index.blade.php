@extends('admin.layout.app')

@section('page_title', 'Equipment Use Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0">All Equipment</h5>
        <a href="{{ route('admin.equipment_uses.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Add Equipment
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">ID</th>
                        <th class="py-3 border-0">Sub Category</th>
                        <th class="py-3 border-0">Equipment Name</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipment as $item)
                    <tr>
                        <td class="px-4">{{ $item->id }}</td>
                        <td><span class="badge bg-light text-dark fw-medium">{{ $item->subCategory->name }}</span></td>
                        <td>{{ $item->name }}</td>
                        <td class="px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.equipment_uses.edit', $item->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">Edit</a>
                                <form action="{{ route('admin.equipment_uses.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 text-danger">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $equipment->links() }}
    </div>
</div>
@endsection
