<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CustomBooking;

class BookingsManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::whereNull('staff_id')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'address', 'items.service', 'items.package']);

        $customQuery = CustomBooking::whereNull('staff_id')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'address']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
            
            $customQuery->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
            $customQuery->whereDate('booking_date', $request->date);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $customQuery->where('status', $request->status);
        }

        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();

        // Tag bookings
        foreach ($bookings as $b) {
            $b->booking_type = 'regular';
        }
        foreach ($customBookings as $cb) {
            $cb->booking_type = 'custom';
        }
        
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at');

        $perPage = $request->get('per_page', 'all');
        if ($perPage != 'all') {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $items = $allBookings->forPage($currentPage, $perPage)->values();
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator($items, $allBookings->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]);
        } else {
            $bookings = $allBookings->values();
        }

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    public function show(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the logged in staff OR is unassigned
        if ($booking->staff_id !== null && $booking->staff_id != $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access to this booking.'
            ], 403);
        }

        $booking->load(['user', 'address', 'items.service', 'items.package']);
        $booking->booking_type = 'regular';
        return response()->json([
            'status' => 'success',
            'data' => $booking
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $user = $request->user();

        if ($booking->staff_id != $user->id) {
            // If it's unassigned and they are trying to accept it
            if ($booking->staff_id === null && $request->status === 'Accepted') {
                $otp = $booking->otp ?? rand(1000, 9999);
                $booking->update([
                    'staff_id' => $user->id,
                    'status' => 'accepted',
                    'otp' => $otp,
                    'verify' => 0
                ]);
                $booking->booking_type = 'regular';
                return response()->json([
                    'status' => 'success',
                    'message' => 'Booking accepted successfully.',
                    'data' => $booking
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:Accepted,On the way,Started,Completed,Rejected'
        ]);

        $statusMap = [
            'Accepted' => 'accepted',
            'On the way' => 'on_the_way',
            'Started' => 'started',
            'Completed' => 'completed',
            'Rejected' => 'cancelled'
        ];

        $dbStatus = $statusMap[$request->status] ?? $request->status;

        $updateData = ['status' => $dbStatus];
        if (($dbStatus === 'accepted' || $dbStatus === 'confirmed') && !$booking->otp) {
            $updateData['otp'] = rand(1000, 9999);
            $updateData['verify'] = 0;
        }

        $booking->update($updateData);
        $booking->booking_type = 'regular';

        return response()->json([
            'status' => 'success',
            'message' => 'Booking status updated to ' . $request->status,
            'data' => $booking
        ]);
    }

    public function customShow(Request $request, $id)
    {
        $user = $request->user();

        $booking = CustomBooking::where(function($q) use ($user) {
                $q->where('staff_id', $user->id)->orWhereNull('staff_id');
            })
            ->with(['user', 'address'])
            ->findOrFail($id);

        $booking->booking_type = 'custom';
        return response()->json([
            'status' => 'success',
            'data' => $booking
        ]);
    }

    public function customUpdateStatus(Request $request, $id)
    {
        $booking = CustomBooking::findOrFail($id);
        $user = $request->user();
        
        if ($booking->staff_id != $user->id) {
            if ($booking->staff_id === null && $request->status === 'Accepted') {
                $otp = $booking->otp ?? rand(1000, 9999);
                $booking->update([
                    'staff_id' => $user->id,
                    'status' => 'accepted',
                    'otp' => $otp,
                    'verify' => 0
                ]);
                $booking->booking_type = 'custom';
                return response()->json([
                    'status' => 'success',
                    'message' => 'Custom booking accepted successfully.',
                    'data' => $booking
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:Accepted,On the way,Started,Completed,Rejected'
        ]);

        $statusMap = [
            'Accepted' => 'accepted',
            'On the way' => 'on_the_way',
            'Started' => 'started',
            'Completed' => 'completed',
            'Rejected' => 'cancelled'
        ];

        $dbStatus = $statusMap[$request->status] ?? $request->status;

        $updateData = ['status' => $dbStatus];
        if (($dbStatus === 'accepted' || $dbStatus === 'confirmed') && !$booking->otp) {
            $updateData['otp'] = rand(1000, 9999);
            $updateData['verify'] = 0;
        }

        $booking->update($updateData);
        $booking->booking_type = 'custom';

        return response()->json([
            'status' => 'success',
            'message' => 'Custom booking status updated to ' . $request->status,
            'data' => $booking
        ]);
    }

    public function verifyOtp(Request $request, Booking $booking)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        if ($booking->otp !== $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.'
            ], 422);
        }

        $booking->update([
            'verify' => 1
        ]);
        $booking->booking_type = 'regular';

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully!',
            'data' => $booking
        ]);
    }

    public function customVerifyOtp(Request $request, $id)
    {
        $booking = CustomBooking::findOrFail($id);
        $request->validate([
            'otp' => 'required'
        ]);

        if ($booking->otp !== $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.'
            ], 422);
        }

        $booking->update([
            'verify' => 1
        ]);
        $booking->booking_type = 'custom';

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully!',
            'data' => $booking
        ]);
    }

    public function pending(Request $request)
    {
        $user = $request->user();

        $query = Booking::where('staff_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'address', 'items.service', 'items.package']);

        $customQuery = CustomBooking::where('staff_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['user', 'address']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
            
            $customQuery->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
            $customQuery->whereDate('booking_date', $request->date);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $customQuery->where('status', $request->status);
        }

        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();

        // Tag bookings
        foreach ($bookings as $b) {
            $b->booking_type = 'regular';
        }
        foreach ($customBookings as $cb) {
            $cb->booking_type = 'custom';
        }
        
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at');

        $perPage = $request->get('per_page', 'all');
        if ($perPage != 'all') {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $items = $allBookings->forPage($currentPage, $perPage)->values();
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator($items, $allBookings->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]);
        } else {
            $bookings = $allBookings->values();
        }

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    public function completed(Request $request)
    {
        $user = $request->user();

        $query = Booking::where('staff_id', $user->id)
            ->where('status', 'completed')
            ->with(['user', 'address', 'items.service', 'items.package']);

        $customQuery = CustomBooking::where('staff_id', $user->id)
            ->where('status', 'completed')
            ->with(['user', 'address']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
            
            $customQuery->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
            $customQuery->whereDate('booking_date', $request->date);
        }

        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();

        // Tag bookings
        foreach ($bookings as $b) {
            $b->booking_type = 'regular';
        }
        foreach ($customBookings as $cb) {
            $cb->booking_type = 'custom';
        }
        
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at');

        $perPage = $request->get('per_page', 'all');
        if ($perPage != 'all') {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $items = $allBookings->forPage($currentPage, $perPage)->values();
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator($items, $allBookings->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]);
        } else {
            $bookings = $allBookings->values();
        }

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    public function cancelled(Request $request)
    {
        $user = $request->user();

        $query = Booking::where('staff_id', $user->id)
            ->where('status', 'cancelled')
            ->with(['user', 'address', 'items.service', 'items.package']);

        $customQuery = CustomBooking::where('staff_id', $user->id)
            ->where('status', 'cancelled')
            ->with(['user', 'address']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
            
            $customQuery->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
            $customQuery->whereDate('booking_date', $request->date);
        }

        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();

        // Tag bookings
        foreach ($bookings as $b) {
            $b->booking_type = 'regular';
        }
        foreach ($customBookings as $cb) {
            $cb->booking_type = 'custom';
        }
        
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at');

        $perPage = $request->get('per_page', 'all');
        if ($perPage != 'all') {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $items = $allBookings->forPage($currentPage, $perPage)->values();
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator($items, $allBookings->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]);
        } else {
            $bookings = $allBookings->values();
        }

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }
}
