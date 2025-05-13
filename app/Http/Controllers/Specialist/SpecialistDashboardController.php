<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialistDashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المتخصص
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $stats = [
            'total_bookings' => Booking::where('specialist_id', $specialist->id)->count(),
            'pending_bookings' => Booking::where('specialist_id', $specialist->id)
                                        ->where('status', 'pending')
                                        ->count(),
            'confirmed_bookings' => Booking::where('specialist_id', $specialist->id)
                                        ->where('status', 'confirmed')
                                        ->count(),
            'cancelled_bookings' => Booking::where('specialist_id', $specialist->id)
                                        ->where('status', 'cancelled')
                                        ->count(),
        ];
        
        $latest_bookings = Booking::with(['user', 'service'])
                                ->where('specialist_id', $specialist->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
        $upcoming_bookings = Booking::with(['user', 'service'])
                                ->where('specialist_id', $specialist->id)
                                ->where('status', 'confirmed')
                                ->where('booking_date', '>=', date('Y-m-d'))
                                ->orderBy('booking_date')
                                ->orderBy('booking_time')
                                ->take(5)
                                ->get();
        
        return view('specialist.dashboard', compact('specialist', 'stats', 'latest_bookings', 'upcoming_bookings'));
    }
    
    /**
     * عرض قائمة الحجوزات للمتخصص
     *
     * @return \Illuminate\Http\Response
     */
    public function bookings(Request $request)
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $query = Booking::with(['user', 'service'])
                    ->where('specialist_id', $specialist->id);
        
        // تطبيق الفلتر حسب الحالة إذا تم تحديدها
        if ($request->has('status') && in_array($request->status, ['pending', 'confirmed', 'cancelled'])) {
            $query->where('status', $request->status);
        }
        
        $bookings = $query->orderBy('booking_date', 'desc')
                          ->paginate(15);
        
        return view('specialist.bookings.index', compact('specialist', 'bookings'));
    }
    
    /**
     * عرض تفاصيل حجز محدد
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBooking($id)
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $booking = Booking::with(['user', 'service'])
                        ->where('specialist_id', $specialist->id)
                        ->findOrFail($id);
        
        return view('specialist.bookings.show', compact('specialist', 'booking'));
    }
    
    /**
     * تأكيد حجز معلق
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmBooking($id)
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $booking = Booking::where('specialist_id', $specialist->id)
                        ->findOrFail($id);
        
        if ($booking->status != 'pending') {
            return redirect()->route('specialist.bookings')
                ->with('error', __('messages.booking_already_processed'));
        }
        
        $booking->status = 'confirmed';
        $booking->save();
        
        // إرسال إشعار للمستخدم بتأكيد الحجز (يمكن إضافته لاحقًا)
        
        return redirect()->route('specialist.bookings')
            ->with('success', __('messages.booking_confirmed'));
    }
    
    /**
     * إلغاء حجز
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelBooking($id)
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $booking = Booking::where('specialist_id', $specialist->id)
                        ->findOrFail($id);
        
        if ($booking->status == 'cancelled') {
            return redirect()->route('specialist.bookings')
                ->with('error', __('messages.booking_already_cancelled'));
        }
        
        $booking->status = 'cancelled';
        $booking->save();
        
        // إرسال إشعار للمستخدم بإلغاء الحجز (يمكن إضافته لاحقًا)
        
        return redirect()->route('specialist.bookings')
            ->with('success', __('messages.booking_cancelled_by_specialist'));
    }
    
    /**
     * عرض الملف الشخصي للمتخصص
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $services = Service::where('is_active', true)->get();
        
        return view('specialist.profile', compact('specialist', 'services'));
    }
    
    /**
     * تحديث الملف الشخصي للمتخصص
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        // الحصول على متخصص مرتبط بالمستخدم الحالي
        $specialist = Specialist::where('email', Auth::user()->email)->first();
        
        if (!$specialist) {
            return redirect()->route('home')
                ->with('error', __('messages.not_a_specialist'));
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'speciality' => 'required|string|max:255',
        ]);
        
        $specialist->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'speciality' => $request->speciality,
        ]);
        
        // معالجة صورة الملف الشخصي إذا تم تحميلها
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $avatarPath = $request->file('avatar')->store('public/specialists');
            $specialist->avatar = str_replace('public/', 'storage/', $avatarPath);
            $specialist->save();
        }
        
        return redirect()->route('specialist.profile')
            ->with('success', __('messages.profile_updated'));
    }
}
