@extends('admin.layout.app')

@section('page_title', 'Offers & Discounts')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0">Active Coupons</h5>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Create Coupon
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Coupon</th>
                        <th class="py-3 border-0">Discount</th>
                        <th class="py-3 border-0 text-center">Min. Order</th>
                        <th class="py-3 border-0 text-center">Expiry</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold text-primary">{{ $coupon->code }}</div>
                            <div class="small text-muted">{{ $coupon->title }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">
                                {{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : '₹' . number_format($coupon->discount_value) }}
                            </div>
                        </td>
                        <td class="text-center">₹{{ number_format($coupon->min_order_amount) }}</td>
                        <td class="text-center">
                            @if($coupon->expiry_date)
                                <span class="{{ $coupon->expiry_date->isPast() ? 'text-danger' : 'text-dark' }} fw-medium">
                                    {{ $coupon->expiry_date->format('d M, Y') }}
                                </span>
                            @else
                                <span class="text-muted">No Expiry</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill {{ $coupon->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">Edit</a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 text-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
