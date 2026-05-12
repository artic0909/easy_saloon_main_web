@extends('admin.layout.app')

@section('page_title', 'Add Customer Feedback')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Feedback Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.feedbacks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Client Name</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Enter client's full name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Star Rating</label>
                        <select name="stars" class="form-select rounded-3" required>
                            <option value="5">5 Stars - Excellent</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="2">2 Stars - Fair</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Testimonial Description</label>
                        <textarea name="description" class="form-control rounded-4" rows="6" placeholder="Paste client's review here..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.profile.feedbacks') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill">Post Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
