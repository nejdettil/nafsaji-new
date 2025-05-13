<x-filament::section>
    <x-slot name="heading">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">تقويم الحجوزات</h2>
            <div class="flex space-x-2 space-x-reverse" dir="rtl">
                <x-filament::button size="sm" wire:click="previousMonth" icon="heroicon-o-chevron-right">
                    الشهر السابق
                </x-filament::button>
                <x-filament::button size="sm" wire:click="goToToday" icon="heroicon-o-calendar">
                    اليوم
                </x-filament::button>
                <x-filament::button size="sm" wire:click="nextMonth" icon-position="after" icon="heroicon-o-chevron-left">
                    الشهر التالي
                </x-filament::button>
            </div>
        </div>
        <div class="mt-2 text-center">
            <h3 class="text-lg font-medium">{{ $currentMonthLabel }}</h3>
        </div>
    </x-slot>
    
    <div class="calendar-container" dir="rtl">
        <div class="grid grid-cols-7 gap-1 mb-2">
            @foreach ($weekdayLabels as $dayLabel)
                <div class="text-center py-2 font-medium bg-gray-100 dark:bg-gray-800 rounded">
                    {{ $dayLabel }}
                </div>
            @endforeach
        </div>
        
        <div class="grid grid-cols-7 gap-1">
            @foreach ($calendarData as $week)
                @foreach ($week as $day)
                    <div class="min-h-[120px] border rounded p-1 {{ $day['isToday'] ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-500' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700' }} {{ $day['day'] ? '' : 'bg-gray-50 dark:bg-gray-900' }}">
                        @if ($day['day'])
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium {{ $day['isToday'] ? 'text-primary-600 dark:text-primary-400' : '' }}">
                                    {{ $day['day'] }}
                                </span>
                                
                                @if (count($day['bookings']) > 0)
                                    <a 
                                        href="{{ route('filament.admin.resources.bookings.index', ['tableFilters[date][value]' => $day['date']]) }}" 
                                        class="text-xs px-1.5 py-0.5 bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-300 rounded-full"
                                    >
                                        {{ count($day['bookings']) }}
                                    </a>
                                @endif
                            </div>
                            
                            <div class="space-y-1 overflow-y-auto max-h-[80px]">
                                @foreach ($day['bookings'] as $booking)
                                    <a 
                                        href="{{ route('filament.admin.resources.bookings.edit', ['record' => $booking->id]) }}" 
                                        class="block text-xs p-1 rounded {{ $this->getStatusColor($booking->status) }} text-white truncate"
                                        title="{{ $booking->user->name }} - {{ $booking->specialist->user->name }} - {{ $booking->service->name }}"
                                    >
                                        {{ \Carbon\Carbon::parse($booking->time)->format('H:i') }} 
                                        {{ Str::limit($booking->user->name, 15) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
    
    <x-slot name="footer">
        <div class="flex flex-wrap gap-2 justify-center" dir="rtl">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-gray-300 mr-1 rounded"></span>
                <span class="text-xs">قيد الانتظار</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-green-500 mr-1 rounded"></span>
                <span class="text-xs">مؤكد</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-blue-500 mr-1 rounded"></span>
                <span class="text-xs">مكتمل</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-red-500 mr-1 rounded"></span>
                <span class="text-xs">ملغي</span>
            </div>
        </div>
    </x-slot>
    
    @push('styles')
        <style>
            .calendar-container {
                max-width: 100%;
                overflow-x: auto;
            }
        </style>
    @endpush
</x-filament::section>
