<x-filament::section>
    <div class="welcome-widget p-4 rounded-xl bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-950/50 dark:to-indigo-950/50 border border-purple-100 dark:border-purple-900">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="welcome-content">
                <h2 class="text-2xl font-bold text-purple-700 dark:text-purple-400">{{ $greeting }}، {{ $userName }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $date }}</p>
                <p class="text-gray-600 dark:text-gray-400 mt-3 max-w-2xl">
                    مرحباً بك في لوحة تحكم نفسجي. من هنا يمكنك إدارة كافة جوانب المنصة بسهولة.
                </p>
            </div>
        </div>

        @php
            $alerts = $this->getAlerts();
        @endphp
        
        @if (count($alerts) > 0)
        <div class="mt-4 space-y-3">
            @foreach($alerts as $alert)
            <div class="p-3 rounded-lg flex items-center gap-3 bg-{{ $alert['color'] }}-50 dark:bg-{{ $alert['color'] }}-900/20 border border-{{ $alert['color'] }}-200 dark:border-{{ $alert['color'] }}-800 text-{{ $alert['color'] }}-700 dark:text-{{ $alert['color'] }}-300">
                <x-dynamic-component :component="$alert['icon']" class="h-6 w-6 flex-shrink-0 text-{{ $alert['color'] }}-600 dark:text-{{ $alert['color'] }}-400" />
                <span class="mr-1">{{ $alert['message'] }}</span>
                @if(isset($alert['url']))
                <a href="{{ $alert['url'] }}" class="mr-auto text-{{ $alert['color'] }}-600 dark:text-{{ $alert['color'] }}-400 font-medium hover:text-{{ $alert['color'] }}-700 dark:hover:text-{{ $alert['color'] }}-300 transition-colors duration-200 flex items-center gap-1">
                    <span>عرض</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <div class="stats-overview mt-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">نظرة عامة</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($this->getStats() as $stat)
                <div class="stat-card bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm flex items-center">
                    <div class="mr-4 p-3 rounded-full bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400">
                        <x-dynamic-component :component="$stat['icon']" class="w-6 h-6" />
                    </div>
                    <div>
                        <h4 class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</h4>
                        <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stat['value'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="quick-links mt-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">روابط سريعة</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($quickLinks as $link)
                    <a href="{{ $link['url'] }}" 
                       class="quick-link flex items-center gap-2 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 hover:translate-y-[-2px] hover:border-{{ $link['color'] }}-200 dark:hover:border-{{ $link['color'] }}-800">
                        <div class="p-2 rounded-full bg-{{ $link['color'] }}-100 dark:bg-{{ $link['color'] }}-900 text-{{ $link['color'] }}-600 dark:text-{{ $link['color'] }}-400">
                            <x-dynamic-component :component="$link['icon']" class="w-5 h-5" />
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $link['title'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-filament::section>
