@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold text-purple-800 mb-6">{{ __('messages.admin_dashboard') }}</h1>
    
    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ __('messages.users') }}</h2>
            <p class="text-3xl font-bold">{{ $stats['users_count'] }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ __('messages.specialists') }}</h2>
            <p class="text-3xl font-bold">{{ $stats['specialists_count'] }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ __('messages.services') }}</h2>
            <p class="text-3xl font-bold">{{ $stats['services_count'] }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ __('messages.total_bookings') }}</h2>
            <p class="text-3xl font-bold">{{ $stats['bookings_count'] }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ __('messages.pending_bookings') }}</h2>
            <p class="text-3xl font-bold">{{ $stats['pending_bookings'] }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-purple-700 mb-2">{{ __('messages.unread_messages') }}</h2>
            <p class="text-3xl font-bold">{{ $stats['new_messages'] }}</p>
        </div>
    </div>
    
    <!-- آخر الحجوزات -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-purple-800">{{ __('messages.latest_bookings') }}</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-purple-600 hover:text-purple-800">
                {{ __('messages.view_all') }} &rarr;
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-purple-100">
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.user') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.specialist') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.service') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.date_time') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.status') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($latest_bookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->specialist->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->service->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ date('Y-m-d', strtotime($booking->booking_date)) }} {{ $booking->booking_time }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($booking->status == 'pending')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">{{ __('messages.booking_status.pending') }}</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ __('messages.booking_status.confirmed') }}</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">{{ __('messages.booking_status.cancelled') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-purple-600 hover:text-purple-800 mr-2">
                                {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            {{ __('messages.no_bookings_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- آخر رسائل الاتصال -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-purple-800">{{ __('messages.latest_messages') }}</h2>
            <a href="{{ route('admin.contacts.index') }}" class="text-purple-600 hover:text-purple-800">
                {{ __('messages.view_all') }} &rarr;
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-purple-100">
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.name') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.email') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.subject') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.date') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.status') }}</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-purple-800">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($latest_contacts as $contact)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contact->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contact->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contact->subject }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $contact->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($contact->is_read)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ __('messages.read') }}</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">{{ __('messages.unread') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="text-purple-600 hover:text-purple-800 mr-2">
                                {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            {{ __('messages.no_messages_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
