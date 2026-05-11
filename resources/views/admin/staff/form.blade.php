@extends('admin.layout.app')

@section('page_title', isset($staff) ? 'Edit Professional' : 'Add New Professional')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">{{ isset($staff) ? 'Update Professional Profile' : 'Professional Details' }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($staff) ? route('admin.staff.update', $staff->id) : route('admin.staff.store') }}" method="POST">
                    @csrf
                    @if(isset($staff))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <!-- Account Info -->
                        <div class="col-12">
                            <h6 class="fw-bold text-secondary border-bottom pb-2 mb-4">Account Information</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $staff->user->name ?? '') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $staff->user->email ?? '') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-2 @error('phone') is-invalid @enderror" value="{{ old('phone', $staff->user->phone ?? '') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Password {{ isset($staff) ? '(Leave blank to keep current)' : '' }}</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2 @error('password') is-invalid @enderror" {{ isset($staff) ? '' : 'required' }}>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Professional Info -->
                        <div class="col-12 mt-5">
                            <h6 class="fw-bold text-secondary border-bottom pb-2 mb-4">Professional Information</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Designation (e.g. Senior Barber, Makeup Artist)</label>
                            <input type="text" name="designation" class="form-control rounded-3 py-2 @error('designation') is-invalid @enderror" value="{{ old('designation', $staff->designation ?? '') }}" required>
                            @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Experience (Years)</label>
                            <input type="number" name="experience_years" class="form-control rounded-3 py-2 @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $staff->experience_years ?? 0) }}" required min="0">
                            @error('experience_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Assigned Salon</label>
                            <select name="salon_id" class="form-select rounded-3 py-2">
                                <option value="">Home Service Only</option>
                                @foreach($salons as $salon)
                                    <option value="{{ $salon->id }}" {{ (old('salon_id', $staff->salon_id ?? '') == $salon->id) ? 'selected' : '' }}>{{ $salon->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Biography</label>
                            <textarea name="bio" class="form-control rounded-3 py-2" rows="4">{{ old('bio', $staff->bio ?? '') }}</textarea>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                {{ isset($staff) ? 'Save Professional Changes' : 'Add Professional' }}
                            </button>
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-light rounded-pill px-5 py-2 fw-bold ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
