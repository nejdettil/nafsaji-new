<div class="px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center gap-4">
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
            <x-heroicon-o-envelope class="w-4 h-4" />
            <span>{{ $totalMessages }} رسالة</span>
        </div>
        
        @if($unreadCount > 0)
            <div class="flex items-center gap-1 text-sm text-danger-500">
                <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                <span>{{ $unreadCount }} غير مقروءة</span>
            </div>
        @endif
    </div>
    
    <a href="{{ $viewAllUrl }}" class="text-sm text-primary-600 hover:text-primary-500 flex items-center gap-1 transition-colors duration-200">
        <span>عرض الكل</span>
        <x-heroicon-o-arrow-left class="w-4 h-4 rtl:rotate-180" />
    </a>
</div>
