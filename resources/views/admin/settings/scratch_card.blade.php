@extends('admin.layout.app')

@section('page_title', 'Scratch Card Free Services')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-4 border-0">
                <h5 class="fw-black mb-0">Select Eligible Services for Free Second Booking</h5>
                <p class="text-muted small mt-2 mb-0">These services will be discounted by 100% when a user claims their scratch card reward on their second booking.</p>
            </div>
            <div class="card-body p-4 pt-0">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.settings.scratch_card.update') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">SELECT SERVICES</label>
                        <select name="services[]" class="form-select select2" multiple="multiple" data-placeholder="Choose free services...">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ in_array($service->id, $freeServiceIds) ? 'selected' : '' }}>
                                    {{ $service->name }} ({{ number_format($service->sale_price, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary rounded-pill py-3 px-5 fw-bold shadow-lg">
                        <i class="bi bi-save-fill me-2"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
