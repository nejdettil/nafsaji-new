<div class="flex items-center">
    <div class="relative w-12 h-12 bg-gradient-to-br from-purple-700 via-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg overflow-hidden border-2 border-white dark:border-gray-800 transition-all duration-300 hover:shadow-purple-500/50">
        <div class="absolute inset-0 bg-gradient-to-tr from-purple-800/30 to-transparent"></div>
        <span class="relative z-10 text-white font-extrabold">{{ app()->getLocale() == 'ar' ? 'ن' : 'N' }}</span>
    </div>
    <div class="flex flex-col rtl:mr-3 ltr:ml-3">
        <span class="text-xl font-black tracking-tight text-purple-700 dark:text-white transition-colors">{{ app()->getLocale() == 'ar' ? 'نفسجي' : 'Nafsaji' }}</span>
        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">لوحة تحكم المدير</span>
    </div>
</div>
