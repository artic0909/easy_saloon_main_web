@extends('admin.layout.app')

@section('page_title', 'Achievement Numbers')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Platform Milestones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Value</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($achievements as $achievement)
                            <tr>
                                <td>
                                    <div class="avatar-sm bg-light d-flex align-items-center justify-content-center text-primary">
                                        <i class="bi bi-{{ $achievement->svg_icon }} fs-5"></i>
                                    </div>
                                </td>
                                <td class="fw-bold">{{ $achievement->title }}</td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $achievement->value }}</span></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form id="delete-form-{{ $achievement->id }}" action="{{ route('admin.profile.numbers.destroy', $achievement->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $achievement->id }})">
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
                <h5 class="fw-bold mb-0">Add Achievement</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.numbers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. PROFESSIONALS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Value</label>
                        <input type="text" name="value" class="form-control rounded-3" placeholder="e.g. 7000+" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Icon</label>
                        <select name="svg_icon" class="form-select select2 rounded-3" required>
                            <option value="people" data-icon="people">People / Professionals</option>
                            <option value="download" data-icon="download">Downloads</option>
                            <option value="calendar-check" data-icon="calendar-check">Bookings</option>
                            <option value="geo-alt" data-icon="geo-alt">Cities</option>
                            <option value="star" data-icon="star">Rating</option>
                            <option value="heart" data-icon="heart">Satisfaction</option>
                            <option value="shop" data-icon="shop">Stores</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mt-3">Add Milestone</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
