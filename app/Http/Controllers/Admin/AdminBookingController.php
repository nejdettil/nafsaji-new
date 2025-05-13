<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * عرض قائمة كل الحجوزات
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'specialist', 'service'])
                    ->orderBy('booking_date', 'desc')
                    ->paginate(15);
        
        return view('admin.bookings.index', compact('bookings'));
    }
    
    /**
     * عرض حجز محدد
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'specialist', 'service']);
        
        return view('admin.bookings.show', compact('booking'));
    }
    
    /**
     * عرض نموذج تعديل الحجز
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        $booking->load(['user', 'specialist', 'service']);
        $specialists = Specialist::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $users = User::all();
        
        return view('admin.bookings.edit', compact('booking', 'specialists', 'services', 'users'));
    }
    
    /**
     * تحديث الحجز المحدد
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $booking->update($request->all());
        
        // يمكن إضافة إرسال إشعار للمستخدم هنا عند تغيير حالة الحجز
        
        return redirect()->route('admin.bookings.index')
                         ->with('success', __('messages.booking_updated'));
    }
    
    /**
     * حذف الحجز المحدد
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        
        return redirect()->route('admin.bookings.index')
                         ->with('success', __('messages.booking_deleted'));
    }
    
    /**
     * تأكيد الحجز
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function confirm(Booking $booking)
    {
        $booking->status = 'confirmed';
        $booking->save();
        
        // يمكن إضافة إرسال إشعار للمستخدم هنا
        
        return redirect()->route('admin.bookings.index')
                         ->with('success', __('messages.booking_confirmed'));
    }
    
    /**
     * إلغاء الحجز
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function cancel(Booking $booking)
    {
        $booking->status = 'cancelled';
        $booking->save();
        
        // يمكن إضافة إرسال إشعار للمستخدم هنا
        
        return redirect()->route('admin.bookings.index')
                         ->with('success', __('messages.booking_cancelled'));
    }
}
