@extends('admin.layout.app')

@section('page_title', 'Edit Equipment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Update Equipment</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.equipment_uses.update', $equipment_use->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Equipment Image</label>
                        @if($equipment_use->image)
                            <div class="mb-2">
                                <img src="{{ asset($equipment_use->image) }}" alt="{{ $equipment_use->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Equipment Name</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $equipment_use->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Update Equipment</button>
                        <a href="{{ route('admin.equipment_uses.index') }}" class="btn btn-light rounded-pill px-5">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
