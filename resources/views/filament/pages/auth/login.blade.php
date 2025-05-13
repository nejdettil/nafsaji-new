<x-filament-panels::page.simple>
    <div dir="rtl" class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-gray-950 dark:to-gray-900">
        <div class="w-full max-w-md space-y-8 p-8 bg-white dark:bg-gray-900 shadow-2xl rounded-2xl border border-purple-100 dark:border-purple-900/30">
            <div class="w-full flex justify-center">
                <div class="relative w-24 h-24 bg-gradient-to-br from-purple-600 via-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-black text-5xl shadow-xl overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 bg-opacity-20"></div>
                    <div class="absolute w-full h-full bg-gradient-to-tr from-purple-800/30 to-transparent"></div>
                    <span class="relative z-10">ن</span>
                </div>
            </div>
            
            <div class="text-center space-y-2">
                <h2 class="text-3xl font-bold tracking-tight text-center text-gray-950 dark:text-white">
                    نفسجي
                </h2>
                <p class="text-gray-500 dark:text-gray-400">منصة العلاج النفسي عن بعد</p>
            </div>
            
            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl border border-purple-100 dark:border-purple-800/30">
                <h2 class="text-center text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    تسجيل الدخول للوحة التحكم
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    أدخل بيانات الاعتماد للوصول إلى لوحة التحكم
                </p>
            </div>
            
            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}
                
                <x-filament::button type="submit" form="authenticate" class="w-full mt-6 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg">
                    تسجيل الدخول
                </x-filament::button>
            </x-filament-panels::form>
            
            <div class="text-center">
                <div class="flex items-center justify-center gap-2 mt-6">
                    <span class="text-gray-700 dark:text-gray-300">تصميم وتطوير</span>
                    <span class="font-semibold text-purple-600 dark:text-purple-400">نفسجي</span>
                </div>

                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    جميع الحقوق محفوظة © {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>

    <x-slot name="footerEnd">
        <div class="flex items-center justify-center py-4">
            <img src="/images/logo.png" alt="Nafsaji Logo" class="h-12 opacity-70 dark:opacity-50 dark:invert transition-opacity hover:opacity-100" onerror="this.src='https://i.imgur.com/xKbGE8J.png'; this.onerror=null;" /> 
        </div>
    </x-slot>
</x-filament-panels::page.simple>
