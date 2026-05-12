@extends('admin.layout.app')

@section('page_title', 'My Profile')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 text-center p-5">
            <div class="avatar-md bg-accent-subtle rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold fs-1 mb-4" style="width: 120px; height: 120px; background: rgba(198, 166, 100, 0.1); color: var(--admin-accent);">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
            <p class="text-muted mb-4">{{ $staff->designation ?? 'Service Professional' }}</p>
            
            <div class="bg-light rounded-4 p-4 text-start mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Experience</span>
                    <span class="fw-bold">{{ $staff->experience_years ?? 0 }} Years</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Rating</span>
                    <span class="text-warning fw-bold"><i class="bi bi-star-fill me-1"></i> {{ $staff->rating ?? '0.0' }}</span>
                </div>
            </div>

            <form action="{{ route('staff.profile.availability') }}" method="POST">
                @csrf
                <div class="form-check form-switch d-flex align-items-center justify-content-between bg-white border rounded-pill px-4 py-3">
                    <label class="form-check-label fw-bold mb-0" for="availabilitySwitchLarge">Availability Status</label>
                    <input class="form-check-input ms-0" type="checkbox" name="is_available" id="availabilitySwitchLarge" 
                        {{ $staff && $staff->is_available ? 'checked' : '' }}
                        onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0">
            <div class="card-header"><h5 class="mb-0 fw-bold">Edit Personal Details</h5></div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('staff.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-2" value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Years of Experience</label>
                            <input type="number" name="experience_years" class="form-control rounded-3 py-2" value="{{ old('experience_years', $staff->experience_years) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Professional Biography</label>
                        <textarea name="bio" class="form-control rounded-3" rows="4">{{ old('bio', $staff->bio) }}</textarea>
                    </div>

                    <hr class="my-4 opacity-50">
                    <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i> Change Password</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 py-2">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3">
                            <i class="bi bi-save me-2"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
