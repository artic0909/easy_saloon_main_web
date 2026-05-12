@extends('admin.layout.app')

@section('page_title', 'Customer Feedbacks')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Client Testimonials</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Client</th>
                                <th>Rating</th>
                                <th>Description</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks as $feedback)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->name) }}&background=f4ece4&color=3d2b1f" class="avatar-sm rounded-circle shadow-sm">
                                        <span class="fw-bold">{{ $feedback->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-warning small">
                                        @for($i=0; $i<$feedback->stars; $i++) <i class="bi bi-star-fill"></i> @endfor
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted small text-truncate" style="max-width: 250px;" title="{{ $feedback->description }}">
                                        {{ $feedback->description }}
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form id="delete-form-{{ $feedback->id }}" action="{{ route('admin.profile.feedbacks.destroy', $feedback->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $feedback->id }})">
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
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Add Feedback</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.feedbacks.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Client Name</label>
                        <input type="text" name="name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rating (Stars)</label>
                        <select name="stars" class="form-select rounded-3" required>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Review Description</label>
                        <textarea name="description" class="form-control rounded-4" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-3">Post Feedback</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
