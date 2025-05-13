<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * المؤشر للوحة التحكم
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'specialists_count' => Specialist::count(),
            'services_count' => Service::count(),
            'bookings_count' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'new_messages' => Contact::where('is_read', false)->count(),
        ];

        $latest_bookings = Booking::with(['user', 'specialist', 'service'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        $latest_contacts = Contact::orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact('stats', 'latest_bookings', 'latest_contacts'));
    }
}
