<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    /**
     * عرض قائمة الرسائل
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('admin.contacts.index', compact('contacts'));
    }
    
    /**
     * عرض رسالة محددة
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        // تحديث حالة القراءة عند فتح الرسالة
        if (!$contact->is_read) {
            $contact->is_read = true;
            $contact->save();
        }
        
        return view('admin.contacts.show', compact('contact'));
    }
    
    /**
     * تحديد الرسالة كمقروءة
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Contact $contact)
    {
        $contact->is_read = true;
        $contact->save();
        
        return redirect()->route('admin.contacts.index')
                         ->with('success', __('messages.contact_marked_as_read'));
    }
    
    /**
     * تحديد الرسالة كغير مقروءة
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function markAsUnread(Contact $contact)
    {
        $contact->is_read = false;
        $contact->save();
        
        return redirect()->route('admin.contacts.index')
                         ->with('success', __('messages.contact_marked_as_unread'));
    }
    
    /**
     * حذف الرسالة المحددة
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
                         ->with('success', __('messages.contact_deleted'));
    }
    
    /**
     * تحديد جميع الرسائل كمقروءة
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        Contact::where('is_read', false)
               ->update(['is_read' => true]);
        
        return redirect()->route('admin.contacts.index')
                         ->with('success', __('messages.all_contacts_marked_as_read'));
    }
}
