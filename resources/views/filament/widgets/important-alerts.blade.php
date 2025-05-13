<x-filament::section>
    <div class="alerts-widget">
        <div class="alerts-header flex justify-between items-center mb-3">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-gray-900 dark:text-white">تنبيهات وإشعارات</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $date }}</span>
            </div>
            
            <button type="button" x-data="{}" x-on:click="$dispatch('notify', { type: 'success', message: 'تم تحديث التنبيهات' }); $wire.$refresh()" 
                class="text-primary-600 hover:text-primary-500 focus:outline-none transition-colors duration-200 flex items-center gap-1">
                <x-heroicon-o-arrow-path class="w-5 h-5" />
                <span class="text-sm">تحديث</span>
            </button>
        </div>
        
        <div class="alerts-content space-y-3">
            @forelse($alerts as $alert)
                <div class="alert-item p-3 bg-white dark:bg-gray-800 rounded-lg border border-{{ $alert['color'] }}-200 dark:border-{{ $alert['color'] }}-800 shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-start gap-3">
                        <div class="alert-icon p-2 rounded-full bg-{{ $alert['color'] }}-100 dark:bg-{{ $alert['color'] }}-900 text-{{ $alert['color'] }}-600 dark:text-{{ $alert['color'] }}-400 flex-shrink-0">
                            <x-dynamic-component :component="$alert['icon']" class="w-6 h-6" />
                        </div>
                        <div class="alert-content flex-grow">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $alert['title'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $alert['message'] }}</p>
                        </div>
                        @if($alert['url'])
                            <a href="{{ $alert['url'] }}" class="p-1 text-{{ $alert['color'] }}-600 hover:text-{{ $alert['color'] }}-500 flex-shrink-0">
                                <x-heroicon-o-arrow-top-right-on-square class="w-5 h-5" />
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert-item p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-gray-500 dark:text-gray-400">لا توجد تنبيهات حالياً</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament::section>
