@extends('admin.layout.app')

@section('page_title', 'Account Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Update Administrative Profile</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4 opacity-50">
                    <p class="small text-muted mb-4"><i class="bi bi-shield-lock me-2"></i> Leave password fields empty if you don't want to change it.</p>

                    <div class="mb-4">
                        <label class="form-label fw-bold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0">
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mt-2">
                        <i class="bi bi-check2-circle me-2"></i> Save Profile Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
