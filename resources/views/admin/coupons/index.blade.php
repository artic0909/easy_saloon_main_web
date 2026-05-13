@extends('admin.layout.app')

@section('page_title', 'Offers & Discounts')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0">Active Coupons</h5>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i> Create Coupon
            </a>
        </div>

        <!-- Filters Area -->
        <form action="{{ route('admin.coupons.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-muted">Search Coupons</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Code or title..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Show Rows</label>
                <select name="per_page" class="form-select">
                    @foreach([10, 20, 50, 100] as $num)
                        <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>{{ $num }} Rows</option>
                    @endforeach
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Show All</option>
                </select>
            </div>

            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">SL</th>
                        <th class="py-3 border-0">Coupon</th>
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
                            @if($coupons instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ ($coupons->currentPage() - 1) * $coupons->perPage() + $loop->iteration }}
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </td>
                        <td>
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
                                @if($coupon->is_active)
                                    <form action="{{ route('admin.coupons.notify', $coupon->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-view" style="color: #6610f2;" title="Notify All Users">
                                            <i class="bi bi-bell-fill"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn-action btn-edit" title="Edit Coupon">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="delete-form-{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $coupon->id }})" title="Delete Coupon">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
