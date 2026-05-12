@extends('admin.layout.app')

@section('page_title', 'Live Location Sharing')

@section('content')
<div class="card border-0 overflow-hidden">
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-4 p-5 bg-primary text-white">
                <h4 class="fw-black mb-4">Share Your Progress</h4>
                <p class="text-white-50 mb-5">Enable location sharing during home services so customers and administrators can track your arrival in real-time.</p>
                
                <div class="d-grid gap-3">
                    <button class="btn btn-white rounded-pill py-3 fw-bold text-primary disabled" title="Feature coming soon">
                        <i class="bi bi-geo-alt-fill me-2"></i> Start Sharing
                    </button>
                    <div class="alert alert-light bg-opacity-10 border-0 text-white small rounded-4 mb-0">
                        <i class="bi bi-info-circle me-2"></i> This feature is currently in development and will be available in the next update.
                    </div>
                </div>
            </div>
            <div class="col-md-8 position-relative" style="min-height: 400px; background: #e5e5e5;">
                <!-- Map Placeholder -->
                <div class="position-absolute inset-0 d-flex flex-column align-items-center justify-content-center text-muted">
                    <i class="bi bi-map display-1 opacity-25 mb-4"></i>
                    <h5 class="fw-bold">Interactive Map Preview</h5>
                    <p class="small">Live tracking visualization will appear here.</p>
                </div>
                
                <!-- Overlay with Blur -->
                <div class="position-absolute inset-0" style="backdrop-filter: grayscale(1) blur(2px); background: rgba(255,255,255,0.2);"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-white {
        background: white;
        color: var(--admin-primary);
        border: none;
    }
    .btn-white:hover {
        background: #f8f9fa;
        transform: translateY(-3px);
    }
</style>
@endsection
