@extends('admin.layout.app')

@section('page_title', 'User Management')

@section('page_actions')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
    <i class="bi bi-person-plus-fill"></i> Add Platform User
</a>
@endsection

@section('content')
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
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-light shadow-sm" title="Edit Profile">
                            <i class="bi bi-pencil-square text-primary"></i>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this account?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-light shadow-sm" title="Remove User">
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
    {{ $users->links() }}
</div>
@endsection
