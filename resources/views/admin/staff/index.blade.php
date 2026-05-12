@extends('admin.layout.app')

@section('page_title', 'Staff Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0">Professional Team</h5>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Add Staff Member
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Professional</th>
                        <th class="py-3 border-0">Designation</th>
                        <th class="py-3 border-0">Salon/Branch</th>
                        <th class="py-3 border-0 text-center">Exp.</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffMembers as $staff)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $staff->name }}</div>
                                    <div class="small text-muted">{{ $staff->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark fw-medium">{{ $staff->designation }}</span></td>
                        <td>{{ $staff->salon->name ?? 'Mobile / Home Service' }}</td>
                        <td class="text-center">{{ $staff->experience_years }} Years</td>
                        <td class="text-center">
                            <span class="badge rounded-pill {{ $staff->is_available ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                                {{ $staff->is_available ? 'Available' : 'Busy' }}
                            </span>
                        </td>
                        <td class="px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">Edit</a>
                                <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
        {{ $staffMembers->links() }}
    </div>
</div>
@endsection
