<div class="flex items-center">
    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md">
        {{ app()->getLocale() == 'ar' ? 'م' : 'S' }}
    </div>
    <span class="ml-2 text-xl font-bold text-primary-600 dark:text-white">{{ app()->getLocale() == 'ar' ? 'المختص' : 'Specialist' }}</span>
</div>
