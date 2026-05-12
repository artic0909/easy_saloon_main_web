@extends('admin.layout.app')

@section('page_title', 'Staff Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">Professional Team</h5>
            <a href="{{ route('admin.staff.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i> Add Staff Member
            </a>
        </div>

        <!-- Filters Area -->
        <form action="{{ route('admin.staff.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-muted">Search Professional</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Name, Email, Phone or Designation..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
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
                            @if($staff->is_available)
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Available
                                </span>
                            @else
                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger border-opacity-25 px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Busy
                                </span>
                            @endif
                        </td>
                        <td class="px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn-action btn-edit" title="Edit Staff">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="delete-form-{{ $staff->id }}" action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $staff->id }})" title="Remove Staff">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
