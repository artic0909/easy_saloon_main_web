@extends('admin.layout.app')

@section('page_title', 'User Management')

@section('page_actions')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
    <i class="bi bi-person-plus-fill"></i> Add Platform User
</a>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-muted">Search Members</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Name, Email or Phone..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Member Profile</th>
                <th>Contact Info</th>
                <th>Platform Role</th>
                <th>Joined Date</th>
                <th class="text-center">Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-md bg-light text-dark d-flex align-items-center justify-content-center fw-bold shadow-sm" style="font-size: 1.2rem;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                            <div class="text-muted small">UID: #ES-{{ 1000 + $user->id }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="small fw-medium text-dark">{{ $user->email }}</div>
                    <div class="text-muted" style="font-size: 11px;">{{ $user->phone ?? 'No Phone' }}</div>
                </td>
                <td>
                    @if($user->role == 'admin')
                        <span class="badge bg-danger-subtle text-danger">Administrator</span>
                    @elseif($user->role == 'staff')
                        <span class="badge bg-primary-subtle text-primary">Service Staff</span>
                    @else
                        <span class="badge bg-success-subtle text-success">Verified User</span>
                    @endif
                </td>
                <td class="small text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input shadow-none" type="checkbox" {{ $user->is_active ? 'checked' : '' }} style="cursor: pointer; width: 40px; height: 20px;">
                    </div>
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit" title="Edit Profile">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $user->id }})" title="Remove User">
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
    {{ $users->links() }}
</div>
@endsection
