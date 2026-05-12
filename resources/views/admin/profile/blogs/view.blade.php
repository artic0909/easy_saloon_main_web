@extends('admin.layout.app')

@section('page_title', 'View Media Story')

@section('page_actions')
<a href="{{ route('admin.profile.media_coverage.edit', $blog->id) }}" class="btn btn-accent rounded-pill px-4">
    <i class="bi bi-pencil me-2"></i> Edit Story
</a>
<a href="{{ route('admin.profile.media_coverage') }}" class="btn btn-light rounded-pill px-4">
    <i class="bi bi-arrow-left me-2"></i> Back to List
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card overflow-hidden">
            <img src="{{ asset('storage/' . $blog->image) }}" class="w-100" style="height: 400px; object-fit: cover;">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        @if($blog->category)
                            <span class="badge bg-accent-subtle text-accent border px-3 py-2 mb-2" style="background: rgba(198, 166, 100, 0.1); color: var(--admin-accent);">{{ $blog->category }}</span>
                        @endif
                        <h2 class="fw-black mb-0">{{ $blog->title }}</h2>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $blog->created_at->format('M d, Y') }}</span>
                </div>
                <hr class="my-4 opacity-50">
                <div class="story-content fs-5 text-muted lh-lg">
                    {!! $blog->description !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
