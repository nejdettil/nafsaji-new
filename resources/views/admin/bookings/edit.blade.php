@extends('layouts.admin')

@section('title', __('messages.edit_booking'))

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-purple-600 hover:text-purple-800 mr-2">
            &larr; {{ __('messages.back_to_booking_details') }}
        </a>
        <h1 class="text-3xl font-bold text-purple-800">{{ __('messages.edit_booking') }}</h1>
    </div>
    
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Selection -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.user') }} *
                        </label>
                        <select id="user_id" name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" required>
                            <option value="">{{ __('messages.select_user') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Specialist Selection -->
                    <div>
                        <label for="specialist_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.specialist') }} *
                        </label>
                        <select id="specialist_id" name="specialist_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" required>
                            <option value="">{{ __('messages.select_specialist') }}</option>
                            @foreach($specialists as $specialist)
                                <option value="{{ $specialist->id }}" {{ $booking->specialist_id == $specialist->id ? 'selected' : '' }}>
                                    {{ $specialist->name }} ({{ $specialist->speciality }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Service Selection -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.service') }} *
                        </label>
                        <select id="service_id" name="service_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" required>
                            <option value="">{{ __('messages.select_service') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $booking->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} ({{ $service->price }} {{ __('messages.currency') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Selection -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.status') }} *
                        </label>
                        <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" required>
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                                {{ __('messages.booking_status.pending') }}
                            </option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>
                                {{ __('messages.booking_status.confirmed') }}
                            </option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>
                                {{ __('messages.booking_status.cancelled') }}
                            </option>
                        </select>
                    </div>
                    
                    <!-- Booking Date -->
                    <div>
                        <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.booking_date') }} *
                        </label>
                        <input type="date" id="booking_date" name="booking_date" 
                               value="{{ date('Y-m-d', strtotime($booking->booking_date)) }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                               required>
                    </div>
                    
                    <!-- Booking Time -->
                    <div>
                        <label for="booking_time" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.booking_time') }} *
                        </label>
                        <input type="time" id="booking_time" name="booking_time" 
                               value="{{ $booking->booking_time }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                               required>
                    </div>
                </div>
                
                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.notes') }}
                    </label>
                    <textarea id="notes" name="notes" rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $booking->notes }}</textarea>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    {{ __('messages.cancel') }}
                </a>
                
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    {{ __('messages.update_booking') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
