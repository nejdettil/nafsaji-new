@extends('layouts.app')

@section('title', __('messages.my_bookings'))

@section('styles')
<style>
    .booking-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .booking-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .booking-card.status-confirmed { border-left-color: #047857; }
    .booking-card.status-pending { border-left-color: #b45309; }
    .booking-card.status-cancelled { border-left-color: #b91c1c; }
    .booking-card.status-completed { border-left-color: #1d4ed8; }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.75rem;
        line-height: 1;
    }
    .badge-confirmed { background-color: #d1fae5; color: #047857; }
    .badge-pending { background-color: #fef3c7; color: #b45309; }
    .badge-cancelled { background-color: #fee2e2; color: #b91c1c; }
    .badge-completed { background-color: #dbeafe; color: #1d4ed8; }
    
    .tab-button {
        padding: 0.75rem 1.25rem;
        border-bottom: 2px solid transparent;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .tab-button.active {
        border-bottom-color: #7e22ce;
        color: #7e22ce;
    }
    .tab-button:hover:not(.active) {
        border-bottom-color: #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-purple-800">{{ __('messages.my_bookings') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('messages.manage_your_appointments') }}</p>
        </div>
        <a href="{{ route('booking.create') }}" class="flex items-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-2.5 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ __('messages.new_booking') }}
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    @if($bookings->isEmpty())
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-8 text-center shadow-sm">
        <img src="{{ asset('images/empty-calendar.svg') }}" alt="No Bookings" class="w-40 h-40 mx-auto mb-6" onerror="this.src='https://api.iconify.design/fluent-emoji:calendar';this.onerror=null;">
        <h3 class="text-xl font-semibold text-purple-800 mb-3">{{ __('messages.no_bookings_yet') }}</h3>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ __('messages.no_bookings_description') }}</p>
        <a href="{{ route('booking.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-md inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ __('messages.book_now') }}
        </a>
    </div>
    @else
    <!-- تصفية الحجوزات بحسب الحالة -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="flex overflow-x-auto scrollbar-hide">
            <button class="tab-button active" data-status="all">{{ __('messages.all_bookings') }}</button>
            <button class="tab-button" data-status="pending">{{ __('messages.pending') }}</button>
            <button class="tab-button" data-status="confirmed">{{ __('messages.confirmed') }}</button>
            <button class="tab-button" data-status="completed">{{ __('messages.completed') }}</button>
            <button class="tab-button" data-status="cancelled">{{ __('messages.cancelled') }}</button>
        </div>
    </div>

    <!-- شريط البحث والتصفية -->
    <div class="bg-white rounded-xl shadow-sm mb-6 p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative w-full md:w-2/3">
                <input type="text" id="booking-search" placeholder="{{ __('messages.search_bookings') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="w-full md:w-1/3">
                <select id="booking-sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                    <option value="date-desc">{{ __('messages.newest_first') }}</option>
                    <option value="date-asc">{{ __('messages.oldest_first') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- قائمة الحجوزات في بطاقات -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8" id="bookings-container">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-xl shadow-sm booking-card status-{{ $booking->status }}" data-status="{{ $booking->status }}" data-search="{{ $booking->specialist->name }} {{ $booking->service->name }}">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full object-cover" 
                                src="{{ $booking->specialist->profile_image ? asset('storage/' . $booking->specialist->profile_image) : asset('images/default-avatar.png') }}" 
                                alt="{{ $booking->specialist->name }}">
                        </div>
                        <div class="ml-4 rtl:mr-4 rtl:ml-0">
                            <h3 class="text-lg font-medium text-gray-900">{{ $booking->specialist->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $booking->specialist->speciality }}</p>
                        </div>
                    </div>
                    <span class="badge badge-{{ $booking->status }}">
                        {{ __('messages.booking_status.' . $booking->status) }}
                    </span>
                </div>
                
                <div class="border-t border-b border-gray-100 py-4 my-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('messages.service') }}</h4>
                            <p class="text-gray-800 font-medium">{{ $booking->service->name }}</p>
                            <p class="text-sm text-purple-600 font-medium mt-1">{{ $booking->service->price }} {{ __('messages.currency') }}</p>
                        </div>
                        
                        <div class="flex-1 min-w-[200px]">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('messages.date_time') }}</h4>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-800">{{ $booking->booking_date->format('Y-m-d') }}</p>
                            </div>
                            <div class="flex items-center mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-800">{{ date('H:i', strtotime($booking->booking_time)) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($booking->notes)
                    <div class="mt-4">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('messages.notes') }}</h4>
                        <p class="text-sm text-gray-600">{{ $booking->notes }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('booking.show', $booking->id) }}" class="text-purple-600 hover:text-purple-800 font-medium flex items-center">
                        <span>{{ __('messages.view_details') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    
                    <div class="flex space-x-2">
                        @if($booking->status == 'pending')
                        <a href="{{ route('booking.edit', $booking->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md hover:bg-indigo-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('messages.edit') }}
                        </a>
                        <form method="POST" action="{{ route('booking.destroy', $booking->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 transition-colors" onclick="return confirm('{{ __('messages.confirm_cancel_booking') }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('messages.cancel') }}
                            </button>
                        </form>
                        @else
                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 rounded-md cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            {{ __('messages.no_actions_available') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- رسالة لا توجد نتائج بحث -->
    <div id="no-results" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mb-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-yellow-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ __('messages.no_bookings_found') }}</h3>
        <p class="text-gray-600">{{ __('messages.try_different_search') }}</p>
    </div>

    <div class="flex justify-center mt-6 mb-8">
        {{ $bookings->links() }}
    </div>

    <!-- رابط عمل حجز جديد في الأسفل -->
    <div class="text-center mb-10">
        <a href="{{ route('booking.create') }}" class="inline-flex items-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ __('messages.book_another_session') }}
        </a>
    </div>
    @endif
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تصفية الحجوزات بحسب الحالة
        const tabButtons = document.querySelectorAll('.tab-button');
        const bookingCards = document.querySelectorAll('.booking-card');
        const noResults = document.getElementById('no-results');
        const searchInput = document.getElementById('booking-search');
        const sortSelect = document.getElementById('booking-sort');
        
        // معالجة النقر على أزرار التبويب
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // إزالة الفئة النشطة من جميع الأزرار
                tabButtons.forEach(btn => btn.classList.remove('active'));
                // إضافة الفئة النشطة للزر المحدد
                button.classList.add('active');
                
                // الحصول على الحالة المطلوبة
                const status = button.getAttribute('data-status');
                
                // تصفية البطاقات
                filterBookings();
            });
        });
        
        // معالجة البحث
        searchInput.addEventListener('input', filterBookings);
        
        // معالجة الفرز
        sortSelect.addEventListener('change', sortBookings);
        
        // وظيفة لتصفية الحجوزات
        function filterBookings() {
            const activeStatus = document.querySelector('.tab-button.active').getAttribute('data-status');
            const searchTerm = searchInput.value.toLowerCase();
            
            let visibleCount = 0;
            
            bookingCards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                const cardSearch = card.getAttribute('data-search').toLowerCase();
                
                // التحقق من الحالة ومصطلح البحث
                const statusMatch = activeStatus === 'all' || cardStatus === activeStatus;
                const searchMatch = cardSearch.includes(searchTerm);
                
                // إظهار أو إخفاء البطاقة
                if (statusMatch && searchMatch) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // إظهار أو إخفاء رسالة عدم وجود نتائج
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
        
        // وظيفة لفرز الحجوزات
        function sortBookings() {
            const container = document.getElementById('bookings-container');
            const cards = Array.from(container.children);
            
            const sortValue = sortSelect.value;
            
            cards.sort((a, b) => {
                const dateA = new Date(a.querySelector('[data-date]')?.getAttribute('data-date') || '2000-01-01');
                const dateB = new Date(b.querySelector('[data-date]')?.getAttribute('data-date') || '2000-01-01');
                
                if (sortValue === 'date-asc') {
                    return dateA - dateB;
                } else {
                    return dateB - dateA;
                }
            });
            
            // إعادة ترتيب البطاقات في DOM
            cards.forEach(card => container.appendChild(card));
        }
    });
</script>
@endsection

@endsection
