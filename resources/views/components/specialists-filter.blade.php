@props(['specialties' => [], 'currentSpecialty' => null])

<div class="specialists-filter bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
    <div class="filter-header mb-4">
        <h3 class="text-lg font-semibold text-gradient">{{ app()->getLocale() == 'ar' ? 'تصفية المتخصصين' : 'Filter Specialists' }}</h3>
    </div>
    
    <!-- تصفية حسب التخصص -->
    <div class="filter-group mb-4">
        <label class="block text-neutral-600 font-medium mb-2 text-sm">
            {{ app()->getLocale() == 'ar' ? 'التخصص' : 'Specialty' }}
        </label>
        <div class="flex flex-wrap gap-2 overflow-x-auto pb-2">
            <a href="{{ route('specialists.index') }}" 
               class="filter-chip {{ $currentSpecialty === null ? 'active' : '' }}">
                {{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}
            </a>
            
            @foreach($specialties as $specialty)
                <a href="{{ route('specialists.index', ['specialty' => $specialty->id]) }}" 
                   class="filter-chip {{ $currentSpecialty == $specialty->id ? 'active' : '' }}">
                    {{ app()->getLocale() == 'ar' ? $specialty->name_ar : $specialty->name_en }}
                </a>
            @endforeach
        </div>
    </div>
    
    <!-- تصفية حسب التقييم -->
    <div class="filter-group mb-4">
        <label class="block text-neutral-600 font-medium mb-2 text-sm">
            {{ app()->getLocale() == 'ar' ? 'التقييم' : 'Rating' }}
        </label>
        <div class="flex items-center">
            <input type="range" min="1" max="5" step="1" value="3" 
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600"
                   id="rating-filter">
            <span class="ml-3 text-primary-700 font-medium rating-value">3+</span>
        </div>
    </div>
    
    <!-- تصفية حسب الحالة -->
    <div class="filter-group mb-4">
        <label class="block text-neutral-600 font-medium mb-2 text-sm">
            {{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}
        </label>
        <div class="flex items-center gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox rounded text-primary-600 focus:ring-primary-500">
                <span class="ml-2 text-sm status-indicator status-online">
                    {{ app()->getLocale() == 'ar' ? 'متصل الآن' : 'Online' }}
                </span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox rounded text-primary-600 focus:ring-primary-500" checked>
                <span class="ml-2 text-sm">
                    {{ app()->getLocale() == 'ar' ? 'متاح اليوم' : 'Available Today' }}
                </span>
            </label>
        </div>
    </div>
    
    <!-- خيارات الترتيب -->
    <div class="filter-group">
        <label class="block text-neutral-600 font-medium mb-2 text-sm">
            {{ app()->getLocale() == 'ar' ? 'ترتيب حسب' : 'Sort By' }}
        </label>
        <select class="search-box w-full p-2 text-sm">
            <option value="rating">{{ app()->getLocale() == 'ar' ? 'التقييم: الأعلى أولاً' : 'Rating: Highest First' }}</option>
            <option value="experience">{{ app()->getLocale() == 'ar' ? 'الخبرة: الأكثر أولاً' : 'Experience: Most First' }}</option>
            <option value="price-asc">{{ app()->getLocale() == 'ar' ? 'السعر: الأقل أولاً' : 'Price: Lowest First' }}</option>
            <option value="price-desc">{{ app()->getLocale() == 'ar' ? 'السعر: الأعلى أولاً' : 'Price: Highest First' }}</option>
        </select>
    </div>
    
    <!-- أزرار التطبيق وإعادة الضبط -->
    <div class="filter-actions mt-6 flex justify-between">
        <button type="button" class="bg-gray-100 hover:bg-gray-200 text-neutral-700 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
            {{ app()->getLocale() == 'ar' ? 'إعادة ضبط' : 'Reset' }}
        </button>
        <button type="button" class="btn-primary py-2 px-6 rounded-lg text-sm">
            {{ app()->getLocale() == 'ar' ? 'تطبيق الفلترة' : 'Apply Filters' }}
        </button>
    </div>
</div>

<script>
    // تحديث قيمة عرض التقييم عند تغيير القيمة
    document.addEventListener('DOMContentLoaded', function() {
        const ratingFilter = document.getElementById('rating-filter');
        const ratingValue = document.querySelector('.rating-value');
        
        if (ratingFilter && ratingValue) {
            ratingFilter.addEventListener('input', function() {
                ratingValue.textContent = this.value + '+';
            });
        }
    });
</script>
