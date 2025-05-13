<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\Notifications\NewContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Store a newly created contact message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);
        
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);
        
        // إرسال إشعار للمشرفين برسالة الاتصال الجديدة
        $admins = User::where('is_admin', true)->get();
        Notification::send($admins, new NewContactMessageNotification($contact));
        
        // يمكن أيضاً إرسال بريد إلكتروني مباشرة
        /*
        Mail::send('emails.contact', ['contact' => $contact], function ($message) use ($contact) {
            $message->to(config('mail.admin_email'))
                    ->subject('رسالة جديدة من موقع نفسجي: ' . $contact->subject);
        });
        */
        
        return redirect()->route('contact.create')
            ->with('success', __('messages.contact_message_sent'));
    }
}
