@extends('layouts.admin')

@section('title', __('messages.booking_details'))

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="text-purple-600 hover:text-purple-800 mr-2">
            &larr; {{ __('messages.back_to_bookings') }}
        </a>
        <h1 class="text-3xl font-bold text-purple-800">{{ __('messages.booking_details') }}</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        {{ __('messages.booking') }} #{{ $booking->id }}
                    </h2>
                    <p class="text-gray-600">
                        {{ __('messages.created_at') }}: {{ $booking->created_at->format('Y-m-d H:i') }}
                    </p>
                </div>
                
                <div>
                    @if($booking->status == 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                            {{ __('messages.booking_status.pending') }}
                        </span>
                    @elseif($booking->status == 'confirmed')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            {{ __('messages.booking_status.confirmed') }}
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                            {{ __('messages.booking_status.cancelled') }}
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">{{ __('messages.user_information') }}</h3>
                    <div class="bg-purple-50 p-4 rounded">
                        <p class="mb-2"><span class="font-semibold">{{ __('messages.name') }}:</span> {{ $booking->user->name }}</p>
                        <p class="mb-2"><span class="font-semibold">{{ __('messages.email') }}:</span> {{ $booking->user->email }}</p>
                        @if($booking->user->phone)
                            <p><span class="font-semibold">{{ __('messages.phone') }}:</span> {{ $booking->user->phone }}</p>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">{{ __('messages.specialist_information') }}</h3>
                    <div class="bg-purple-50 p-4 rounded">
                        <p class="mb-2"><span class="font-semibold">{{ __('messages.name') }}:</span> {{ $booking->specialist->name }}</p>
                        <p class="mb-2"><span class="font-semibold">{{ __('messages.speciality') }}:</span> {{ $booking->specialist->speciality }}</p>
                        <p><span class="font-semibold">{{ __('messages.email') }}:</span> {{ $booking->specialist->email }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-purple-700 mb-2">{{ __('messages.booking_information') }}</h3>
                <div class="bg-purple-50 p-4 rounded">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="mb-2"><span class="font-semibold">{{ __('messages.service') }}:</span> {{ $booking->service->name }}</p>
                            <p class="mb-2"><span class="font-semibold">{{ __('messages.price') }}:</span> {{ $booking->service->price }} {{ __('messages.currency') }}</p>
                            <p><span class="font-semibold">{{ __('messages.duration') }}:</span> {{ $booking->service->duration }} {{ __('messages.minutes') }}</p>
                        </div>
                        <div>
                            <p class="mb-2"><span class="font-semibold">{{ __('messages.booking_date') }}:</span> {{ date('Y-m-d', strtotime($booking->booking_date)) }}</p>
                            <p><span class="font-semibold">{{ __('messages.booking_time') }}:</span> {{ $booking->booking_time }}</p>
                        </div>
                    </div>
                    
                    @if($booking->notes)
                        <div class="mt-4 border-t pt-4 border-purple-200">
                            <p class="font-semibold mb-2">{{ __('messages.notes') }}:</p>
                            <p class="text-gray-700">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-between">
            <div>
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ __('messages.edit_booking') }}
                </a>
            </div>
            
            <div class="flex space-x-2">
                @if($booking->status == 'pending')
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            {{ __('messages.confirm_booking') }}
                        </button>
                    </form>
                @endif
                
                @if($booking->status != 'cancelled')
                    <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('{{ __('messages.confirm_cancel_booking') }}')">
                            {{ __('messages.cancel_booking') }}
                        </button>
                    </form>
                @endif
                
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('{{ __('messages.confirm_delete_booking') }}')">
                        {{ __('messages.delete_booking') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
