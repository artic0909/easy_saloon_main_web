@extends('admin.layout.app')

@section('page_title', 'Customer Feedbacks')

@section('page_actions')
<a href="{{ route('admin.profile.feedbacks.create') }}" class="btn btn-primary rounded-pill px-4">
    <i class="bi bi-plus-lg me-2"></i> Add Feedback
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Rating</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->name) }}&background=f4ece4&color=3d2b1f" class="avatar-sm rounded-circle shadow-sm">
                                <span class="fw-bold text-dark">{{ $feedback->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-warning">
                                @for($i=0; $i<$feedback->stars; $i++) <i class="bi bi-star-fill"></i> @endfor
                            </div>
                        </td>
                        <td>
                            <div class="text-muted small text-truncate" style="max-width: 300px;" title="{{ $feedback->description }}">
                                {{ $feedback->description }}
                            </div>
                        </td>
                        <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.profile.feedbacks.edit', $feedback->id) }}" class="btn-action btn-edit" title="Edit Feedback">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="delete-form-{{ $feedback->id }}" action="{{ route('admin.profile.feedbacks.destroy', $feedback->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $feedback->id }})" title="Delete Feedback">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted italic">No feedbacks found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection
