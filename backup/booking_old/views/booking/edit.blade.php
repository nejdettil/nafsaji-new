@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
@endsection

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-purple-800 mb-6">{{ __('messages.edit_booking') }}</h1>

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('booking.update', $booking->id) }}" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="specialist_id" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ __('messages.select_specialist') }} *
                </label>
                <select id="specialist_id" name="specialist_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    <option value="">{{ __('messages.choose_specialist') }}</option>
                    @foreach($specialists as $specialist)
                    <option value="{{ $specialist->id }}" {{ old('specialist_id', $booking->specialist_id) == $specialist->id ? 'selected' : '' }}>
                        {{ $specialist->name }} - {{ $specialist->speciality }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-6">
                <label for="service_id" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ __('messages.select_service') }} *
                </label>
                <select id="service_id" name="service_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    <option value="">{{ __('messages.choose_service') }}</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id', $booking->service_id) == $service->id ? 'selected' : '' }}
                            data-price="{{ $service->price }}" data-duration="{{ $service->duration }}">
                        {{ $service->name }} - {{ $service->price }} {{ __('messages.currency') }} ({{ $service->duration }} {{ __('messages.minutes') }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="booking_date" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('messages.booking_date') }} *
                    </label>
                    <input type="date" id="booking_date" name="booking_date" 
                           value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required
                           min="{{ date('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                </div>
                
                <div>
                    <label for="booking_time" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('messages.booking_time') }} *
                    </label>
                    <input type="time" id="booking_time" name="booking_time" 
                           value="{{ old('booking_time', date('H:i', strtotime($booking->booking_time))) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ __('messages.notes') }}
                </label>
                <textarea id="notes" name="notes" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">{{ old('notes', $booking->notes) }}</textarea>
                <p class="text-gray-500 text-xs mt-1">{{ __('messages.notes_privacy') }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-md mb-6" id="booking-summary">
                <h3 class="text-lg font-semibold text-purple-800 mb-2">{{ __('messages.booking_summary') }}</h3>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="font-medium">{{ __('messages.service') }}:</div>
                    <div id="summary-service">{{ $booking->service->name }}</div>
                    
                    <div class="font-medium">{{ __('messages.price') }}:</div>
                    <div id="summary-price">{{ $booking->service->price }} {{ __('messages.currency') }}</div>
                    
                    <div class="font-medium">{{ __('messages.duration') }}:</div>
                    <div id="summary-duration">{{ $booking->service->duration }} {{ __('messages.minutes') }}</div>
                    
                    <div class="font-medium">{{ __('messages.date_time') }}:</div>
                    <div id="summary-datetime">{{ $booking->formatted_date_time }}</div>
                </div>
            </div>
            
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-md mb-6">
                <p class="text-amber-700 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('messages.edit_booking_warning') }}
                </p>
            </div>
            
            <div class="flex justify-between">
                <a href="{{ route('booking.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                    {{ __('messages.update_booking') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const bookingDate = document.getElementById('booking_date');
        const bookingTime = document.getElementById('booking_time');
        const summaryService = document.getElementById('summary-service');
        const summaryPrice = document.getElementById('summary-price');
        const summaryDuration = document.getElementById('summary-duration');
        const summaryDatetime = document.getElementById('summary-datetime');
        
        function updateSummary() {
            if (serviceSelect.value && bookingDate.value && bookingTime.value) {
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const serviceName = selectedOption.text.split(' - ')[0];
                const price = selectedOption.getAttribute('data-price');
                const duration = selectedOption.getAttribute('data-duration');
                const date = new Date(bookingDate.value + 'T' + bookingTime.value);
                
                summaryService.textContent = serviceName;
                summaryPrice.textContent = price + ' {{ __('messages.currency') }}';
                summaryDuration.textContent = duration + ' {{ __('messages.minutes') }}';
                summaryDatetime.textContent = 
                    date.toLocaleDateString('{{ app()->getLocale() }}', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) + ' ' + 
                    date.toLocaleTimeString('{{ app()->getLocale() }}', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
            }
        }
        
        serviceSelect.addEventListener('change', updateSummary);
        bookingDate.addEventListener('change', updateSummary);
        bookingTime.addEventListener('change', updateSummary);
    });
</script>
@endsection
