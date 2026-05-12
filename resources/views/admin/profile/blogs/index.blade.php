@extends('admin.layout.app')

@section('page_title', 'Media Coverage')

@section('page_actions')
<a href="{{ route('admin.profile.media_coverage.create') }}" class="btn btn-primary rounded-pill px-4">
    <i class="bi bi-plus-lg me-2"></i> Add Story
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Source / Title</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $blog->image) }}" class="rounded-3" style="width: 80px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $blog->title }}</div>
                            @if($blog->category)
                                <div class="badge bg-light text-muted border px-2 py-1 mb-1" style="font-size: 10px;">{{ $blog->category }}</div>
                            @endif
                            <div class="text-muted small">{!! Str::limit(strip_tags($blog->description), 50) !!}</div>
                        </td>
                        <td>{{ $blog->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.profile.media_coverage.show', $blog->id) }}" class="btn-action btn-view" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.profile.media_coverage.edit', $blog->id) }}" class="btn-action btn-edit" title="Edit Story">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="delete-form-{{ $blog->id }}" action="{{ route('admin.profile.media_coverage.destroy', $blog->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $blog->id }})" title="Delete Story">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted italic">No media stories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection
