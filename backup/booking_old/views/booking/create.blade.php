@extends('layouts.app')

@section('styles')
<style>
    .step-container {
        display: none;
    }
    .step-container.active {
        display: block;
    }
    .step-indicator {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #f3f4f6;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        position: relative;
    }
    .step-indicator.active {
        background-color: #8b5cf6;
        color: white;
    }
    .step-indicator.completed {
        background-color: #10b981;
        color: white;
    }
    .step-connector {
        flex-grow: 1;
        height: 2px;
        background-color: #e5e7eb;
        margin: 0 5px;
    }
    .step-connector.active {
        background-color: #8b5cf6;
    }
    .specialist-card, .service-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .specialist-card:hover, .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .specialist-card.selected, .service-card.selected {
        border-color: #8b5cf6;
        background-color: #f5f3ff;
    }
    .time-slot {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .time-slot:hover {
        background-color: #f3f4f6;
    }
    .time-slot.selected {
        background-color: #8b5cf6;
        color: white;
    }
    .time-slot.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f3f4f6;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-purple-800 mb-6 text-center">{{ __('messages.book_new_session') }}</h1>

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <!-- مؤشرات الخطوات -->
        <div class="flex items-center justify-center mb-8">
            <div class="step-indicator completed" id="step-indicator-1">1</div>
            <div class="step-connector" id="connector-1-2"></div>
            <div class="step-indicator" id="step-indicator-2">2</div>
            <div class="step-connector" id="connector-2-3"></div>
            <div class="step-indicator" id="step-indicator-3">3</div>
            <div class="step-connector" id="connector-3-4"></div>
            <div class="step-indicator" id="step-indicator-4">4</div>
        </div>
        
        <!-- شريط العنوان للخطوة الحالية -->
        <div class="bg-purple-100 text-purple-800 text-center py-3 rounded-t-lg font-semibold mb-1" id="step-title">
            {{ __('messages.choose_specialist') }}
        </div>

        <form method="POST" action="{{ route('booking.store') }}" class="bg-white shadow-md rounded-b-lg p-6" id="booking-form">
            @csrf
            <!-- حقول مخفية للمعلومات المختارة -->
            <input type="hidden" name="specialist_id" id="specialist_id_input" value="{{ old('specialist_id') ?? (isset($selectedSpecialist) ? $selectedSpecialist->id : '') }}">
            <input type="hidden" name="service_id" id="service_id_input" value="{{ old('service_id') ?? (isset($selectedService) ? $selectedService->id : '') }}">
            <input type="hidden" name="booking_date" id="booking_date_input" value="{{ old('booking_date') }}">
            <input type="hidden" name="booking_time" id="booking_time_input" value="{{ old('booking_time') }}">
            
            <!-- الخطوة الأولى: اختيار المختص -->
            <div class="step-container active" id="step-1">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($specialists as $specialist)
                    <div class="specialist-card bg-white rounded-lg shadow-sm p-4 text-center {{ (old('specialist_id') == $specialist->id || (isset($selectedSpecialist) && $selectedSpecialist->id == $specialist->id)) ? 'selected' : '' }}" 
                         data-id="{{ $specialist->id }}" onclick="selectSpecialist(this, {{ $specialist->id }})">
                        <div class="mx-auto h-24 w-24 mb-3 rounded-full overflow-hidden bg-gray-100">
                            @if($specialist->profile_image)
                                <img src="{{ $specialist->profile_image }}" alt="{{ $specialist->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-purple-100 text-purple-600 text-lg font-semibold">
                                    {{ substr($specialist->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $specialist->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $specialist->speciality }}</p>
                        <div class="flex items-center justify-center text-yellow-500 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($specialist->rating ?? 4))
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">{{ $specialist->experience ?? 5 }}+ {{ __('messages.years_experience') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-between">
                    <a href="{{ route('booking.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="button" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed" 
                            id="next-step-1" disabled onclick="nextStep(1)">
                        {{ __('messages.next') }}
                    </button>
                </div>
            </div>
            
            <!-- الخطوة الثانية: اختيار الخدمة -->
            <div class="step-container" id="step-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($services as $service)
                    <div class="service-card bg-white rounded-lg border border-gray-200 p-4 {{ (old('service_id') == $service->id || (isset($selectedService) && $selectedService->id == $service->id)) ? 'selected' : '' }}" 
                         data-id="{{ $service->id }}" data-price="{{ $service->price }}" data-duration="{{ $service->duration }}" 
                         onclick="selectService(this, {{ $service->id }})">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-12 w-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $service->name }}</h3>
                                <div class="flex items-center mt-1 mb-2">
                                    <span class="text-gray-600 text-sm mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $service->duration }} {{ __('messages.minutes') }}
                                    </span>
                                    <span class="text-purple-800 font-semibold">
                                        {{ $service->price }} {{ __('messages.currency') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $service->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-between">
                    <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400" onclick="prevStep(2)">
                        {{ __('messages.previous') }}
                    </button>
                    <button type="button" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed" 
                            id="next-step-2" disabled onclick="nextStep(2)">
                        {{ __('messages.next') }}
                    </button>
                </div>
            </div>
            
            <!-- الخطوة الثالثة: اختيار التاريخ والوقت -->
            <div class="step-container" id="step-3">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">
                        {{ __('messages.select_date') }}:
                    </label>
                    
                    <div id="date-selector" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 gap-2 mb-6">
                        @php
                            $today = new DateTime();
                            $dates = [];
                            
                            for($i = 0; $i < 14; $i++) {
                                $date = clone $today;
                                $date->modify("+$i days");
                                $dates[] = $date;
                            }
                        @endphp
                        
                        @foreach($dates as $date)
                            <div class="date-option border border-gray-200 rounded-lg p-2 text-center cursor-pointer hover:bg-purple-50 hover:border-purple-300 transition-colors"
                                 data-date="{{ $date->format('Y-m-d') }}"
                                 onclick="selectDate(this, '{{ $date->format('Y-m-d') }}')">
                                <div class="text-xs text-gray-600">{{ $date->format('D') }}</div>
                                <div class="font-semibold text-gray-800 text-lg">{{ $date->format('j') }}</div>
                                <div class="text-xs text-gray-600">{{ $date->format('M') }}</div>
                            </div>
                        @endforeach
                    </div>
                    
                    <label class="block text-gray-700 font-semibold mb-3">
                        {{ __('messages.select_time') }}:
                    </label>
                    
                    <div id="time-selector" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                        @php
                            $times = [
                                '09:00', '10:00', '11:00', '12:00', '13:00', '14:00',
                                '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',
                            ];
                        @endphp
                        
                        @foreach($times as $time)
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center"
                                 data-time="{{ $time }}"
                                 onclick="selectTime(this, '{{ $time }}')">
                                {{ $time }}
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-8 flex justify-between">
                    <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400" onclick="prevStep(3)">
                        {{ __('messages.previous') }}
                    </button>
                    <button type="button" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed" 
                            id="next-step-3" disabled onclick="nextStep(3)">
                        {{ __('messages.next') }}
                    </button>
                </div>
            </div>
            
            <!-- الخطوة الرابعة: تفاصيل إضافية والتأكيد -->
            <div class="step-container" id="step-4">
                @if(!Auth::check())
                <!-- عندما يكون المستخدم غير مسجل الدخول، نطلب بياناته الشخصية -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                    <div class="flex items-start mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-blue-700">{{ __('messages.guest_booking_info') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="guest_name" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('messages.name') }} *
                            </label>
                            <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        <div>
                            <label for="guest_email" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('messages.email') }} *
                            </label>
                            <input type="email" id="guest_email" name="guest_email" value="{{ old('guest_email') }}" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="guest_phone" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ __('messages.phone') }} *
                        </label>
                        <input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div class="mt-4 flex items-center">
                        <input type="checkbox" id="create_account" name="create_account" class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="create_account" class="ml-2 text-sm text-gray-700">
                            {{ __('messages.create_account_from_booking') }}
                        </label>
                    </div>
                </div>
                @endif

                <div class="mb-6">
                    <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('messages.notes') }}
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">{{ old('notes') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">{{ __('messages.notes_privacy') }}</p>
                </div>
                
                <div class="bg-purple-50 p-5 rounded-lg mb-6" id="booking-summary">
                    <h3 class="text-lg font-semibold text-purple-800 mb-3">{{ __('messages.booking_summary') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <div class="text-gray-500 mb-1">{{ __('messages.specialist') }}</div>
                                <div class="font-medium text-gray-800" id="summary-specialist">-</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <div class="text-gray-500 mb-1">{{ __('messages.service') }}</div>
                                <div class="font-medium text-gray-800" id="summary-service">-</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="text-gray-500 mb-1">{{ __('messages.date_time') }}</div>
                                <div class="font-medium text-gray-800" id="summary-datetime">-</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <div class="text-gray-500 mb-1">{{ __('messages.price') }}</div>
                                <div class="font-medium text-gray-800" id="summary-price">-</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-between">
                    <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400" onclick="prevStep(4)">
                        {{ __('messages.previous') }}
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                        {{ __('messages.confirm_booking') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تعريف المتغيرات للوصول إلى عناصر النموذج
        const specialistCards = document.querySelectorAll('.specialist-card');
        const serviceCards = document.querySelectorAll('.service-card');
        const dateOptions = document.querySelectorAll('.date-option');
        const timeSlots = document.querySelectorAll('.time-slot');
        
        const specialistInput = document.getElementById('specialist_id_input');
        const serviceInput = document.getElementById('service_id_input');
        const dateInput = document.getElementById('booking_date_input');
        const timeInput = document.getElementById('booking_time_input');
        
        const stepButtons = {
            1: document.getElementById('next-step-1'),
            2: document.getElementById('next-step-2'),
            3: document.getElementById('next-step-3')
        };
        
        const summarySpecialist = document.getElementById('summary-specialist');
        const summaryService = document.getElementById('summary-service');
        const summaryDatetime = document.getElementById('summary-datetime');
        const summaryPrice = document.getElementById('summary-price');
        
        const stepTitle = document.getElementById('step-title');
        
        // البيانات المختارة
        let selectedData = {
            specialist: {
                id: null,
                name: null
            },
            service: {
                id: null,
                name: null,
                price: null,
                duration: null
            },
            date: null,
            time: null
        };
        
        // دالة اختيار المختص
        window.selectSpecialist = function(element, id) {
            // إزالة التحديد من جميع البطاقات
            specialistCards.forEach(card => card.classList.remove('selected'));
            
            // تحديد البطاقة الحالية
            element.classList.add('selected');
            
            // تحديث البيانات المختارة
            selectedData.specialist.id = id;
            selectedData.specialist.name = element.querySelector('h3').textContent;
            specialistInput.value = id;
            
            // تفعيل زر الخطوة التالية
            stepButtons[1].disabled = false;
            
            // تحديث الملخص
            updateSummary();
        };
        
        // دالة اختيار الخدمة
        window.selectService = function(element, id) {
            // إزالة التحديد من جميع البطاقات
            serviceCards.forEach(card => card.classList.remove('selected'));
            
            // تحديد البطاقة الحالية
            element.classList.add('selected');
            
            // تحديث البيانات المختارة
            selectedData.service.id = id;
            selectedData.service.name = element.querySelector('h3').textContent;
            selectedData.service.price = element.getAttribute('data-price');
            selectedData.service.duration = element.getAttribute('data-duration');
            serviceInput.value = id;
            
            // تفعيل زر الخطوة التالية
            stepButtons[2].disabled = false;
            
            // تحديث الملخص
            updateSummary();
        };
        
        // دالة اختيار التاريخ
        window.selectDate = function(element, date) {
            // إزالة التحديد من جميع خيارات التاريخ
            dateOptions.forEach(option => option.classList.remove('bg-purple-100', 'border-purple-400'));
            
            // تحديد الخيار الحالي
            element.classList.add('bg-purple-100', 'border-purple-400');
            
            // تحديث البيانات المختارة
            selectedData.date = date;
            dateInput.value = date;
            
            // التحقق مما إذا كان يمكن الانتقال إلى الخطوة التالية
            checkDateTimeSelection();
            
            // تحديث الملخص
            updateSummary();
        };
        
        // دالة اختيار الوقت
        window.selectTime = function(element, time) {
            // إزالة التحديد من جميع فترات الوقت
            timeSlots.forEach(slot => slot.classList.remove('selected'));
            
            // تحديد الفترة الحالية
            element.classList.add('selected');
            
            // تحديث البيانات المختارة
            selectedData.time = time;
            timeInput.value = time;
            
            // التحقق مما إذا كان يمكن الانتقال إلى الخطوة التالية
            checkDateTimeSelection();
            
            // تحديث الملخص
            updateSummary();
        };
        
        // التحقق من اكتمال اختيار التاريخ والوقت
        function checkDateTimeSelection() {
            if (selectedData.date && selectedData.time) {
                stepButtons[3].disabled = false;
            } else {
                stepButtons[3].disabled = true;
            }
        }
        
        // الانتقال إلى الخطوة التالية
        window.nextStep = function(currentStep) {
            // إخفاء الخطوة الحالية
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            
            // إظهار الخطوة التالية
            document.getElementById(`step-${currentStep + 1}`).classList.add('active');
            
            // تحديث مؤشرات الخطوات
            document.getElementById(`step-indicator-${currentStep}`).classList.add('completed');
            document.getElementById(`step-indicator-${currentStep + 1}`).classList.add('active');
            document.getElementById(`connector-${currentStep}-${currentStep + 1}`).classList.add('active');
            
            // تحديث عنوان الخطوة
            updateStepTitle(currentStep + 1);
        };
        
        // الرجوع إلى الخطوة السابقة
        window.prevStep = function(currentStep) {
            // إخفاء الخطوة الحالية
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            
            // إظهار الخطوة السابقة
            document.getElementById(`step-${currentStep - 1}`).classList.add('active');
            
            // تحديث مؤشرات الخطوات
            document.getElementById(`step-indicator-${currentStep}`).classList.remove('active');
            document.getElementById(`connector-${currentStep - 1}-${currentStep}`).classList.remove('active');
            
            // تحديث عنوان الخطوة
            updateStepTitle(currentStep - 1);
        };
        
        // تحديث عنوان الخطوة
        function updateStepTitle(step) {
            switch(step) {
                case 1:
                    stepTitle.textContent = '{{ __('messages.choose_specialist') }}';
                    break;
                case 2:
                    stepTitle.textContent = '{{ __('messages.choose_service') }}';
                    break;
                case 3:
                    stepTitle.textContent = '{{ __('messages.choose_date_time') }}';
                    break;
                case 4:
                    stepTitle.textContent = '{{ __('messages.review_and_confirm') }}';
                    break;
            }
        }
        
        // تحديث ملخص الحجز
        function updateSummary() {
            // المختص
            if (selectedData.specialist.name) {
                summarySpecialist.textContent = selectedData.specialist.name;
            }
            
            // الخدمة
            if (selectedData.service.name) {
                summaryService.textContent = selectedData.service.name;
                summaryPrice.textContent = selectedData.service.price + ' {{ __('messages.currency') }}';
            }
            
            // التاريخ والوقت
            if (selectedData.date && selectedData.time) {
                const date = new Date(selectedData.date + 'T' + selectedData.time);
                summaryDatetime.textContent = 
                    date.toLocaleDateString('{{ app()->getLocale() }}', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) + ' ' + 
                    selectedData.time;
            }
        }
        
        // تحميل البيانات المحفوظة مسبقًا (إذا وجدت)
        function loadSavedData() {
            // المختص
            if (specialistInput.value) {
                const specialistCard = document.querySelector(`.specialist-card[data-id="${specialistInput.value}"]`);
                if (specialistCard) {
                    selectSpecialist(specialistCard, specialistInput.value);
                }
            }
            
            // الخدمة
            if (serviceInput.value) {
                const serviceCard = document.querySelector(`.service-card[data-id="${serviceInput.value}"]`);
                if (serviceCard) {
                    selectService(serviceCard, serviceInput.value);
                }
            }
            
            // التاريخ
            if (dateInput.value) {
                const dateOption = document.querySelector(`.date-option[data-date="${dateInput.value}"]`);
                if (dateOption) {
                    selectDate(dateOption, dateInput.value);
                }
            }
            
            // الوقت
            if (timeInput.value) {
                const timeSlot = document.querySelector(`.time-slot[data-time="${timeInput.value}"]`);
                if (timeSlot) {
                    selectTime(timeSlot, timeInput.value);
                }
            }
        }
        
        // تهيئة النموذج
        function initForm() {
            // تفعيل مؤشر الخطوة الأولى
            document.getElementById('step-indicator-1').classList.add('active');
            
            // تحميل البيانات المحفوظة
            loadSavedData();
        }
        
        // تهيئة النموذج عند تحميل الصفحة
        initForm();
    });
</script>
@endsection
