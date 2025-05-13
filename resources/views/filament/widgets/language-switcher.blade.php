<div class="p-2 bg-purple-50 rounded-lg">
    <div class="flex items-center justify-between">
        <span class="text-sm font-bold text-purple-800">
            {{ __('messages.language') }}
        </span>
        <div class="flex space-x-2">
            <button 
                wire:click="switchLanguage('ar')" 
                class="px-3 py-1 {{ app()->getLocale() == 'ar' ? 'bg-purple-500 text-white' : 'bg-white text-purple-500' }} rounded-md text-sm">
                العربية
            </button>
            <button 
                wire:click="switchLanguage('en')" 
                class="px-3 py-1 {{ app()->getLocale() == 'en' ? 'bg-purple-500 text-white' : 'bg-white text-purple-500' }} rounded-md text-sm">
                English
            </button>
        </div>
    </div>
</div>
