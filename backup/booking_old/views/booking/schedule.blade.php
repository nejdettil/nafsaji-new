@extends('booking.layout')

@section('booking_styles')
<style>
    .schedule-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .calendar-container {
        margin-bottom: 2rem;
    }
    
    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }
    
    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    
    .month-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-dark);
    }
    
    .calendar-nav {
        display: flex;
        gap: 0.5rem;
    }
    
    .calendar-nav button {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f3f4f6;
        border-radius: 9999px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .calendar-nav button:hover {
        background-color: #e5e7eb;
    }
    
    .calendar-nav button svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .weekday {
        text-align: center;
        font-weight: 600;
        color: var(--text-light);
        font-size: 0.875rem;
    }
    
    .day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 500;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }
    
    .day.available {
        background-color: #e9d5ff;
        color: var(--primary-dark);
        cursor: pointer;
        border: 2px solid var(--primary-color);
        box-shadow: 0 2px 5px rgba(126, 34, 206, 0.2);
        font-weight: bold;
    }
    
    .day.available:hover {
        background-color: #f3e8ff;
        border-color: var(--primary-light);
    }
    
    .day.selected {
        background-color: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-dark);
    }
    
    .day.unavailable {
        opacity: 0.3;
        cursor: not-allowed;
        color: var(--text-light);
        background-color: #f3f4f6;
    }
    
    .day.past {
        opacity: 0.3;
        cursor: not-allowed;
        color: var(--text-light);
        background-color: #f3f4f6;
    }
    
    .day.empty {
        visibility: hidden;
    }
    
    .time-slots-container {
        margin-top: 2rem;
    }
    
    .time-slots-header {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary-dark);
    }
    
    .time-slots {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }
    
    @media (max-width: 640px) {
        .time-slots {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .time-slot {
        padding: 0.75rem;
        text-align: center;
        border-radius: 0.375rem;
        cursor: pointer;
        font-weight: 500;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    
    .time-slot.available {
        background-color: #f8f5ff;
        color: var(--primary-color);
        border-color: #e9d5ff;
    }
    
    .time-slot.available:hover {
        background-color: #f3e8ff;
        border-color: var(--primary-light);
    }
    
    .time-slot.selected {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-dark);
    }
    
    .time-slot.unavailable {
        opacity: 0.3;
        cursor: not-allowed;
        color: var(--text-light);
        background-color: #f3f4f6;
    }
    
    .booking-actions {
        margin-top: 3rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--primary-color);
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .back-link:hover {
        color: var(--primary-dark);
    }
    
    .back-link svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
    }
    
    .summary-section {
        background-color: #f8f5ff;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 2rem;
    }
    
    .summary-title {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1rem;
    }
    
    .summary-item {
        display: flex;
        margin-bottom: 0.5rem;
    }
    
    .summary-label {
        width: 40%;
        font-weight: 500;
        color: var(--text-light);
    }
    
    .summary-value {
        width: 60%;
        font-weight: 600;
        color: var(--text-dark);
    }
</style>
@endsection

@section('booking_content')
<div class="schedule-container">
    <a href="{{ route('booking.services', $specialist->id) }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ __('messages.back_to_services') }}
    </a>
    
    <div class="summary-section">
        <h3 class="summary-title">{{ __('messages.booking_summary') }}</h3>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.specialist') }}:</div>
            <div class="summary-value">{{ $specialist->name }}</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.service') }}:</div>
            <div class="summary-value">{{ $service->name }}</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.duration') }}:</div>
            <div class="summary-value">{{ $service->duration }} {{ __('messages.minutes') }}</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.price') }}:</div>
            <div class="summary-value">{{ number_format($service->price, 2) }} {{ __('messages.currency_sar') }}</div>
        </div>
    </div>
    
    <div class="calendar-container">
        <div class="calendar-header">
            <h3 class="month-title" id="monthYear">{{ __('messages.loading_calendar') }}</h3>
            <div class="calendar-nav">
                <button id="prevMonth">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="nextMonth">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="weekdays">
            <div class="weekday">{{ __('messages.sun') }}</div>
            <div class="weekday">{{ __('messages.mon') }}</div>
            <div class="weekday">{{ __('messages.tue') }}</div>
            <div class="weekday">{{ __('messages.wed') }}</div>
            <div class="weekday">{{ __('messages.thu') }}</div>
            <div class="weekday">{{ __('messages.fri') }}</div>
            <div class="weekday">{{ __('messages.sat') }}</div>
        </div>
        
        <div class="calendar" id="calendar">
            <!-- Calendar days will be generated here -->
        </div>
    </div>
    
    <div class="time-slots-container" id="timeSlotsContainer" style="display: none;">
        <h3 class="time-slots-header">{{ __('messages.available_times_for') }} <span id="selectedDate"></span></h3>
        
        <div class="time-slots" id="timeSlots">
            <!-- Time slots will be generated here -->
        </div>
    </div>
    
    <div class="booking-actions">
        <a href="{{ route('booking.services', $specialist->id) }}" class="btn btn-outline">
            {{ __('messages.back') }}
        </a>
        
        <form action="{{ route('booking.confirm') }}" method="POST" id="scheduleForm">
            @csrf
            <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <input type="hidden" name="booking_date" id="bookingDate">
            <input type="hidden" name="booking_time" id="bookingTime">
            
            <button type="submit" class="btn btn-primary" id="continueBtn" disabled>
                {{ __('messages.continue') }}
            </button>
        </form>
    </div>
</div>
@endsection

@section('booking_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const specialistId = {{ $specialist->id }};
        const serviceId = {{ $service->id }};
        const serviceDuration = {{ $service->duration }};
        
        let currentDate = new Date();
        let selectedDate = null;
        let selectedTimeSlot = null;
        const availableDates = {}; // Will store available dates and times
        
        // Format date to YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        
        // Format date for display
        function formatDateDisplay(date) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('{{ app()->getLocale() }}', options);
        }
        
        // Generate calendar for a given month and year
        function generateCalendar(month, year) {
            const monthYearText = new Date(year, month).toLocaleDateString('{{ app()->getLocale() }}', { month: 'long', year: 'numeric' });
            document.getElementById('monthYear').textContent = monthYearText;
            
            const calendarEl = document.getElementById('calendar');
            calendarEl.innerHTML = '';
            
            const today = new Date();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            
            // Start from Sunday (0) to match our calendar layout
            let startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
            
            // Add empty cells for days before the first day of the month
            for (let i = 0; i < startingDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.classList.add('day', 'empty');
                calendarEl.appendChild(emptyDay);
            }
            
            // Add days of the month
            for (let i = 1; i <= daysInMonth; i++) {
                const dayDate = new Date(year, month, i);
                const dateStr = formatDate(dayDate);
                
                const dayEl = document.createElement('div');
                dayEl.classList.add('day');
                dayEl.textContent = i;
                dayEl.setAttribute('data-date', dateStr);
                
                // Check if date is in the past
                if (dayDate < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
                    dayEl.classList.add('past');
                } 
                // يوم من 0 (الأحد) إلى 4 (الخميس) - جعل كل الأيام من الأحد إلى الخميس متاحة
                else if (dayDate.getDay() >= 0 && dayDate.getDay() <= 4) {
                    dayEl.classList.add('available');
                    
                    // إضافة مستمع حدث النقر لتحديد التاريخ وإظهار المواعيد
                    dayEl.addEventListener('click', () => {
                        // إذا لم تكن هناك مواعيد متاحة لهذا اليوم، نضيف مواعيد افتراضية
                        if (!availableDates[dateStr] || availableDates[dateStr].length === 0) {
                            availableDates[dateStr] = [];
                            // إضافة مواعيد من 9 صباحاً إلى 5 مساءً
                            for (let hour = 9; hour < 17; hour++) {
                                availableDates[dateStr].push((hour < 10 ? '0' : '') + hour + ':00');
                            }
                        }
                        
                        selectDate(dateStr, dayDate);
                    });
                } else {
                    dayEl.classList.add('unavailable');
                }
                
                // Mark selected date
                if (selectedDate && dateStr === formatDate(selectedDate)) {
                    dayEl.classList.add('selected');
                }
                
                calendarEl.appendChild(dayEl);
            }
        }
        
        // Select a date and show time slots
        function selectDate(dateStr, date) {
            // Deselect previously selected date
            const previouslySelected = document.querySelector('.day.selected');
            if (previouslySelected) {
                previouslySelected.classList.remove('selected');
            }
            
            // Select new date
            selectedDate = date;
            document.querySelector(`.day[data-date="${dateStr}"]`).classList.add('selected');
            
            // Update hidden input
            document.getElementById('bookingDate').value = dateStr;
            
            // Reset time slot selection
            selectedTimeSlot = null;
            document.getElementById('bookingTime').value = '';
            document.getElementById('continueBtn').disabled = true;
            
            // Show and populate time slots
            const timeSlotContainer = document.getElementById('timeSlotsContainer');
            timeSlotContainer.style.display = 'block';
            
            document.getElementById('selectedDate').textContent = formatDateDisplay(date);
            
            generateTimeSlots(dateStr);
        }
        
        // Generate time slots for a given date
        function generateTimeSlots(dateStr) {
            const timeSlotsEl = document.getElementById('timeSlots');
            timeSlotsEl.innerHTML = '';
            
            if (!availableDates[dateStr] || availableDates[dateStr].length === 0) {
                const noSlots = document.createElement('div');
                noSlots.textContent = "{{ __('messages.no_available_slots') }}";
                noSlots.classList.add('no-slots');
                timeSlotsEl.appendChild(noSlots);
                return;
            }
            
            availableDates[dateStr].forEach(timeSlot => {
                const slotEl = document.createElement('div');
                slotEl.classList.add('time-slot', 'available');
                slotEl.textContent = timeSlot;
                slotEl.setAttribute('data-time', timeSlot);
                
                slotEl.addEventListener('click', () => selectTimeSlot(timeSlot, slotEl));
                
                timeSlotsEl.appendChild(slotEl);
            });
        }
        
        // Select a time slot
        function selectTimeSlot(timeSlot, element) {
            // Deselect previously selected time slot
            const previouslySelected = document.querySelector('.time-slot.selected');
            if (previouslySelected) {
                previouslySelected.classList.remove('selected');
            }
            
            // Select new time slot
            selectedTimeSlot = timeSlot;
            element.classList.add('selected');
            
            // Update hidden input
            document.getElementById('bookingTime').value = timeSlot;
            document.getElementById('continueBtn').disabled = false;
        }
        
        // Navigate to previous month
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
            // Fetch availability data for the new month
            fetchAvailability(currentDate.getMonth(), currentDate.getFullYear());
        });
        
        // Navigate to next month
        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
            // Fetch availability data for the new month
            fetchAvailability(currentDate.getMonth(), currentDate.getFullYear());
        });
        
        // Fetch availability data from the server
        function fetchAvailability(month, year) {
            // Get the first and last day of the month
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            
            const startDate = formatDate(firstDay);
            const endDate = formatDate(lastDay);
            
            fetch(`/api/availability?specialist_id=${specialistId}&service_id=${serviceId}&start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    console.log('API Response:', data);
                    availableDates = data.availability || {};
                    console.log('Available Dates:', availableDates);
                    
                    // Regenerate calendar with availability data
                    generateCalendar(month, year);
                    
                    // If there was a selected date, check if it's still in the current month
                    if (selectedDate && selectedDate.getMonth() === month && selectedDate.getFullYear() === year) {
                        const dateStr = formatDate(selectedDate);
                        if (availableDates[dateStr]) {
                            // Re-select the date and show time slots
                            selectDate(dateStr, selectedDate);
                        } else {
                            // Deselect if date is no longer available
                            selectedDate = null;
                            document.getElementById('timeSlotsContainer').style.display = 'none';
                            document.getElementById('bookingDate').value = '';
                            document.getElementById('bookingTime').value = '';
                            document.getElementById('continueBtn').disabled = true;
                        }
                    } else {
                        // Hide time slots if selected date is not in current month
                        selectedDate = null;
                        document.getElementById('timeSlotsContainer').style.display = 'none';
                        document.getElementById('bookingDate').value = '';
                        document.getElementById('bookingTime').value = '';
                        document.getElementById('continueBtn').disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching availability:', error);
                });
        }
        
        // Initialize calendar
        generateCalendar(currentDate.getMonth(), currentDate.getFullYear());
        
        // Initial fetch of availability data
        fetchAvailability(currentDate.getMonth(), currentDate.getFullYear());
    });
</script>
@endsection
