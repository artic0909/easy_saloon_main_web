@extends('admin.layout.app')

@section('page_title', isset($coupon) ? 'Edit Coupon' : 'Create New Coupon')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">{{ isset($coupon) ? 'Update Offer Configuration' : 'Coupon Generation' }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}" method="POST">
                    @csrf
                    @if(isset($coupon))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Coupon Code (Uppercase)</label>
                            <input type="text" name="code" class="form-control rounded-3 py-2 text-uppercase @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code ?? '') }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Offer Title</label>
                            <input type="text" name="title" class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" value="{{ old('title', $coupon->title ?? '') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Type</label>
                            <select name="discount_type" class="form-select rounded-3 py-2" required>
                                <option value="percentage" {{ (old('discount_type', $coupon->discount_type ?? '') == 'percentage') ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ (old('discount_type', $coupon->discount_type ?? '') == 'fixed') ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Value</label>
                            <input type="number" name="discount_value" class="form-control rounded-3 py-2" value="{{ old('discount_value', $coupon->discount_value ?? '') }}" required min="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Min. Order Amount (₹)</label>
                            <input type="number" name="min_order_amount" class="form-control rounded-3 py-2" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}" min="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control rounded-3 py-2" value="{{ old('expiry_date', isset($coupon->expiry_date) ? $coupon->expiry_date->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Short Description</label>
                            <input type="text" name="short_description" class="form-control rounded-3 py-2" value="{{ old('short_description', $coupon->short_description ?? '') }}">
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold">This coupon is currently active</label>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                {{ isset($coupon) ? 'Save Offer Changes' : 'Generate Coupon' }}
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light rounded-pill px-5 py-2 fw-bold ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
