<x-filament::page>
    <div>
        <div class="mb-6 flex justify-end">
            <x-filament::button wire:click="refreshInfo" wire:loading.attr="disabled" icon="heroicon-o-arrow-path">
                تحديث المعلومات
            </x-filament::button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" dir="rtl">
            <!-- معلومات النظام -->
            <x-filament::section>
                <x-slot name="heading">معلومات النظام</x-slot>
                
                <ul class="space-y-2">
                    @foreach ($systemInfo as $key => $value)
                        <li class="flex justify-between">
                            <span class="font-medium">{{ $key }}</span>
                            <span>{{ $value }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-filament::section>
            
            <!-- معلومات PHP -->
            <x-filament::section>
                <x-slot name="heading">معلومات PHP</x-slot>
                
                <ul class="space-y-2">
                    @foreach ($phpInfo as $key => $value)
                        <li class="flex justify-between">
                            <span class="font-medium">{{ $key }}</span>
                            <span>{{ $value }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-filament::section>
            
            <!-- معلومات قاعدة البيانات -->
            <x-filament::section>
                <x-slot name="heading">معلومات قاعدة البيانات</x-slot>
                
                <ul class="space-y-2">
                    @foreach ($databaseInfo as $key => $value)
                        <li class="flex justify-between">
                            <span class="font-medium">{{ $key }}</span>
                            <span>{{ $value }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-filament::section>
            
            <!-- معلومات ذاكرة التخزين المؤقت -->
            <x-filament::section>
                <x-slot name="heading">معلومات ذاكرة التخزين المؤقت</x-slot>
                
                <ul class="space-y-2">
                    @foreach ($cacheInfo as $key => $value)
                        <li class="flex justify-between">
                            <span class="font-medium">{{ $key }}</span>
                            <span>{{ $value }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-filament::section>
            
            <!-- معلومات مساحة القرص -->
            <x-filament::section class="col-span-1 md:col-span-2">
                <x-slot name="heading">معلومات مساحة القرص</x-slot>
                
                <div class="space-y-6">
                    <ul class="space-y-2">
                        @foreach ($diskSpace as $key => $value)
                            <li class="flex justify-between">
                                <span class="font-medium">{{ $key }}</span>
                                <span>{{ $value }}</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    @if(isset($diskSpace['نسبة الاستخدام']))
                        @php
                            $usagePercentage = (int) $diskSpace['نسبة الاستخدام'];
                            $colorClass = 'bg-success-500';
                            
                            if ($usagePercentage > 90) {
                                $colorClass = 'bg-danger-500';
                            } elseif ($usagePercentage > 70) {
                                $colorClass = 'bg-warning-500';
                            } elseif ($usagePercentage > 50) {
                                $colorClass = 'bg-primary-500';
                            }
                        @endphp
                        
                        <div class="w-full bg-gray-200 rounded-full h-4 mt-4">
                            <div class="{{ $colorClass }} h-4 rounded-full" style="width: {{ $usagePercentage }}%"></div>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament::page>
