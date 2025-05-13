<x-filament-panels::page>
    @if ($this->hasHeader())
        <div class="dashboard-header mb-6">
            <div class="bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-primary-950/50 dark:to-secondary-950/50 p-4 rounded-xl border border-primary-100 dark:border-primary-900 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-primary-700 dark:text-primary-400">
                            {{ $this->getHeading() }}
                        </h1>
                        
                        @if ($this->getSubheading())
                            <p class="mt-1 text-gray-600 dark:text-gray-400">
                                {{ $this->getSubheading() }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <x-filament::button
                            color="primary"
                            icon="heroicon-m-arrow-path"
                            wire:click="$refresh"
                            wire:loading.attr="disabled"
                            class="shadow-sm"
                        >
                            تحديث البيانات
                        </x-filament::button>

                        <x-filament::button
                            href="{{ route('filament.admin.resources.bookings.index') }}"
                            color="primary"
                            icon="heroicon-m-calendar"
                            class="shadow-sm"
                            outlined
                        >
                            إدارة الحجوزات
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($this->hasHeaderWidgets())
        <x-filament-widgets::widgets
            :columns="$this->getHeaderWidgetsColumns()"
            :data="$this->getHeaderWidgetsData()"
            :widgets="$this->getVisibleHeaderWidgets()"
            class="mb-6"
        />
    @endif

    <div class="dashboard-body flex flex-col lg:grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if (count($mainWidgets = $this->getVisibleWidgets()))
                <x-filament-widgets::widgets
                    :columns="$this->getWidgetsColumns()"
                    :data="$this->getWidgetsData()"
                    :widgets="collect($mainWidgets)->filter(fn ($widget) => 
                        in_array(get_class($widget), [
                            'App\\Filament\\Widgets\\BookingsChart',
                            'App\\Filament\\Widgets\\PaymentsChart',
                            'App\\Filament\\Widgets\\LatestBookingsWidget'
                        ])
                    )->toArray()"
                />
            @endif
        </div>
        
        <div class="space-y-6">
            @if (count($sideWidgets = $this->getVisibleWidgets()))
                <x-filament-widgets::widgets
                    :columns="1"
                    :data="$this->getWidgetsData()"
                    :widgets="collect($sideWidgets)->filter(fn ($widget) => 
                        in_array(get_class($widget), [
                            'App\\Filament\\Widgets\\FinancialStatsWidget',
                            'App\\Filament\\Widgets\\LatestContactsWidget'
                        ])
                    )->toArray()"
                />
            @endif
            
            <!-- قسم الروابط السريعة -->
            <x-filament::section>
                <x-slot name="heading">الروابط السريعة</x-slot>
                
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('filament.admin.resources.users.index') }}" class="p-4 flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow transition-all duration-200 hover:border-primary-200 dark:hover:border-primary-800">
                        <x-heroicon-o-user-group class="w-8 h-8 text-primary-600 dark:text-primary-400 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">إدارة المستخدمين</span>
                    </a>
                    
                    <a href="{{ route('filament.admin.resources.specialists.index') }}" class="p-4 flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow transition-all duration-200 hover:border-success-200 dark:hover:border-success-800">
                        <x-heroicon-o-academic-cap class="w-8 h-8 text-success-600 dark:text-success-400 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">إدارة المختصين</span>
                    </a>
                    
                    <a href="{{ route('filament.admin.resources.services.index') }}" class="p-4 flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow transition-all duration-200 hover:border-info-200 dark:hover:border-info-800">
                        <x-heroicon-o-briefcase class="w-8 h-8 text-info-600 dark:text-info-400 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">إدارة الخدمات</span>
                    </a>
                    
                    <a href="{{ route('filament.admin.resources.payments.index') }}" class="p-4 flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow transition-all duration-200 hover:border-warning-200 dark:hover:border-warning-800">
                        <x-heroicon-o-banknotes class="w-8 h-8 text-warning-600 dark:text-warning-400 mb-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">إدارة المدفوعات</span>
                    </a>
                </div>
            </x-filament::section>
        </div>
    </div>

    @if ($this->hasFooterWidgets())
        <x-filament-widgets::widgets
            :columns="$this->getFooterWidgetsColumns()"
            :data="$this->getFooterWidgetsData()"
            :widgets="$this->getVisibleFooterWidgets()"
            class="mt-6"
        />
    @endif

    <script>
        // تفعيل الرسوم البيانية للإحصائيات
        document.addEventListener('DOMContentLoaded', function() {
            // تحسين مظهر الرسوم البيانية
            const style = document.createElement('style');
            style.textContent = `
                .fi-wi-stats-overview-stat-chart {
                    height: 4rem !important;
                }
                
                .fi-section {
                    transition: all 0.2s ease-in-out;
                }
                
                .fi-section:hover {
                    transform: translateY(-2px);
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-filament-panels::page>
