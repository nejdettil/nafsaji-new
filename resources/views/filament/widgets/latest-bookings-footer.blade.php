<div class="px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex flex-wrap justify-between items-center gap-4">
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-1 text-sm">
            <span class="px-2 py-1 rounded-full bg-warning-100 text-warning-700 dark:bg-warning-900 dark:text-warning-300 flex items-center gap-1">
                <x-heroicon-o-clock class="w-4 h-4" />
                <span>{{ $pendingBookings }} قيد الانتظار</span>
            </span>
        </div>
        
        <div class="flex items-center gap-1 text-sm">
            <span class="px-2 py-1 rounded-full bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300 flex items-center gap-1">
                <x-heroicon-o-check-circle class="w-4 h-4" />
                <span>{{ $confirmedBookings }} مؤكد</span>
            </span>
        </div>
    </div>
    
    <div class="flex items-center">
        <a href="{{ $viewAllUrl }}" class="text-sm text-primary-600 hover:text-primary-500 flex items-center gap-1 transition-colors duration-200">
            <span>عرض كل الحجوزات</span>
            <x-heroicon-o-arrow-left class="w-4 h-4 rtl:rotate-180" />
        </a>
    </div>
</div>
