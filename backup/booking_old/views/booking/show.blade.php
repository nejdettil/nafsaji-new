@extends('layouts.app')

@section('title', __('messages.booking_details'))

@section('styles')
<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-confirmed {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-completed {
        background-color: #e0e7ff;
        color: #3730a3;
    }
    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('booking.index') }}" class="text-purple-600 hover:text-purple-800 transition-colors flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                {{ __('messages.back_to_bookings') }}
            </a>
        </div>
        <h1 class="text-3xl font-bold text-purple-800 mt-2">{{ __('messages.booking_details') }}</h1>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <!-- Booking Header with Status -->
        <div class="bg-purple-50 p-6 border-b border-purple-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-semibold text-purple-900">
                            {{ __('messages.appointment_with') }} {{ $booking->specialist->name }}
                        </h2>
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ __('messages.status_' . $booking->status) }}
                        </span>
                    </div>
                    <p class="text-purple-700 mt-1">{{ $booking->service->name }}</p>
                </div>
                
                <div class="flex space-x-2">
                    @if($booking->status == 'pending')
                    <a href="{{ route('booking.edit', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('messages.edit_booking') }}
                    </a>
                    <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors" onclick="return confirm('{{ __('messages.confirm_cancel_booking') }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('messages.cancel_booking') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Booking Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Booking Date & Time -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ __('messages.appointment_date_time') }}</h3>
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, j F Y') }}
                            </p>
                            <p class="text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Specialist Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ __('messages.specialist') }}</h3>
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 flex-shrink-0">
                            @if($booking->specialist->profile_image)
                                <img src="{{ $booking->specialist->profile_image }}" alt="{{ $booking->specialist->name }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-purple-600 font-medium text-sm">
                                        {{ substr($booking->specialist->name, 0, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-gray-800 font-medium">{{ $booking->specialist->name }}</p>
                            <p class="text-gray-600">{{ $booking->specialist->specialty }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Service Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ __('messages.service') }}</h3>
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <div>
                            <p class="text-gray-800 font-medium">{{ $booking->service->name }}</p>
                            <p class="text-gray-600">{{ __('messages.duration') }}: {{ $booking->service->duration }} {{ __('messages.minutes') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Price Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ __('messages.price') }}</h3>
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-gray-800 font-medium">{{ $booking->service->price }} {{ __('messages.currency') }}</p>
                            <p class="text-gray-600">
                                @if($booking->payment_status == 'paid')
                                    <span class="text-green-600 font-medium">{{ __('messages.payment_paid') }}</span>
                                @elseif($booking->payment_status == 'pending')
                                    <span class="text-yellow-600 font-medium">{{ __('messages.payment_pending') }}</span>
                                @else
                                    <span class="text-gray-600 font-medium">{{ __('messages.payment_not_processed') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notes Section -->
            @if($booking->notes)
            <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ __('messages.notes') }}</h3>
                <p class="text-gray-700">{{ $booking->notes }}</p>
            </div>
            @endif
            
            <!-- Payment Button (if applicable) -->
            @if($booking->status == 'confirmed' && (!isset($booking->payment_status) || $booking->payment_status != 'paid'))
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.complete_payment') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('messages.payment_instructions') }}</p>
                <a href="{{ route('payment.checkout', ['booking_id' => $booking->id]) }}" 
                   class="inline-block bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition-colors">
                    {{ __('messages.pay_now') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any JavaScript needed for the booking details page
</script>
@endsection
