@props(['placeholder' => null, 'action' => null, 'searchParam' => 'query'])

@php
    $placeholderText = $placeholder ?? (app()->getLocale() == 'ar' ? 'ابحث عن متخصص أو خدمة...' : 'Search for specialists or services...');
    $searchAction = $action ?? route('search');
@endphp

<div class="nafsaji-search-container relative">
    <form action="{{ $searchAction }}" method="GET" class="w-full">
        <div class="search-box flex items-center bg-white border border-neutral-200 p-2 rounded-full w-full focus-within:border-primary-400 focus-within:ring-2 focus-within:ring-primary-100 transition-all">
            <div class="search-icon flex-shrink-0 mx-2 text-neutral-400">
                <i class="fas fa-search"></i>
            </div>
            
            <input type="text" 
                   name="{{ $searchParam }}" 
                   placeholder="{{ $placeholderText }}" 
                   class="flex-grow border-0 focus:ring-0 text-neutral-800 placeholder-neutral-400 text-sm focus:outline-none"
                   autocomplete="off">
            
            <button type="submit" class="search-button flex-shrink-0 bg-primary-600 text-white rounded-full p-2 hover:bg-primary-700 transition-colors">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
            </button>
        </div>
    </form>
    
    <!-- اقتراحات البحث - تظهر عند الكتابة -->
    <div class="search-suggestions hidden absolute top-full mt-2 inset-x-0 bg-white rounded-xl shadow-lg border border-neutral-100 z-10 max-h-80 overflow-y-auto">
        <div class="py-2">
            <!-- العناوين المقترحة -->
            <div class="px-4 py-2">
                <h6 class="text-xs font-medium text-neutral-500 mb-2">{{ app()->getLocale() == 'ar' ? 'اقتراحات البحث' : 'Suggestions' }}</h6>
                <div class="space-y-1">
                    <a href="#" class="block p-2 hover:bg-neutral-50 rounded text-sm text-neutral-800 transition-colors">
                        <i class="fas fa-search text-primary-400 mr-2 opacity-70"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'استشارات نفسية' : 'Psychological counseling' }}</span>
                    </a>
                    <a href="#" class="block p-2 hover:bg-neutral-50 rounded text-sm text-neutral-800 transition-colors">
                        <i class="fas fa-search text-primary-400 mr-2 opacity-70"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'علاج القلق والتوتر' : 'Anxiety treatment' }}</span>
                    </a>
                    <a href="#" class="block p-2 hover:bg-neutral-50 rounded text-sm text-neutral-800 transition-colors">
                        <i class="fas fa-search text-primary-400 mr-2 opacity-70"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'التوجيه الأسري' : 'Family counseling' }}</span>
                    </a>
                </div>
            </div>
            
            <!-- متخصصون شائعون -->
            <div class="border-t border-neutral-100 px-4 py-2 mt-1">
                <h6 class="text-xs font-medium text-neutral-500 mb-2">{{ app()->getLocale() == 'ar' ? 'متخصصون مميزون' : 'Featured Specialists' }}</h6>
                <div class="space-y-2">
                    <a href="#" class="flex items-center p-2 hover:bg-neutral-50 rounded transition-colors">
                        <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden mr-3 flex-shrink-0">
                            <img src="{{ asset('images/specialists/avatar1.jpg') }}" alt="Specialist" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <div class="text-sm font-medium text-neutral-800">{{ app()->getLocale() == 'ar' ? 'د. محمد أحمد' : 'Dr. Mohammed Ahmed' }}</div>
                            <div class="text-xs text-neutral-500">{{ app()->getLocale() == 'ar' ? 'استشاري نفسي' : 'Psychological Consultant' }}</div>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-2 hover:bg-neutral-50 rounded transition-colors">
                        <div class="w-8 h-8 rounded-full bg-neutral-200 overflow-hidden mr-3 flex-shrink-0">
                            <img src="{{ asset('images/specialists/avatar2.jpg') }}" alt="Specialist" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <div class="text-sm font-medium text-neutral-800">{{ app()->getLocale() == 'ar' ? 'د. سارة يوسف' : 'Dr. Sara Yousef' }}</div>
                            <div class="text-xs text-neutral-500">{{ app()->getLocale() == 'ar' ? 'معالج أسري' : 'Family Therapist' }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // البحث الفوري مع الاقتراحات
        const searchInput = document.querySelector('.nafsaji-search-container input');
        const suggestionsPanel = document.querySelector('.search-suggestions');
        
        if (searchInput && suggestionsPanel) {
            // إظهار الاقتراحات عند التركيز
            searchInput.addEventListener('focus', function() {
                if (this.value.length > 0) {
                    suggestionsPanel.classList.remove('hidden');
                }
            });
            
            // إظهار الاقتراحات عند الكتابة
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    suggestionsPanel.classList.remove('hidden');
                } else {
                    suggestionsPanel.classList.add('hidden');
                }
            });
            
            // إخفاء الاقتراحات عند النقر خارجها
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nafsaji-search-container')) {
                    suggestionsPanel.classList.add('hidden');
                }
            });
        }
    });
</script>
