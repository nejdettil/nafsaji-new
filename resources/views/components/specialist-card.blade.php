@props(['variant' => 'light'])

@php
// تحديد الألوان والأنماط بناءً على المتغير variant
$cardBg = $variant === 'dark' ? 'bg-gray-800' : 'bg-white';
$textColor = $variant === 'dark' ? 'text-white' : 'text-gray-800';
$borderColor = $variant === 'dark' ? 'border-gray-700' : 'border-gray-100';
$subtleTextColor = $variant === 'dark' ? 'text-gray-300' : 'text-gray-600';

// تحديد الأيقونات والأنماط الإضافية
$ratingColor = 'text-yellow-400';
$ctaBgClass = 'bg-gradient-to-r from-purple-600 to-indigo-600';
$ctaText = app()->getLocale() == 'ar' ? 'حجز موعد' : 'Book Appointment';

// تحديد نمط البطاقة بناءً على المتغير mode
$cardClasses = $mode === 'compact' ? 'h-64' : ($mode === 'horizontal' ? 'flex flex-row' : 'flex flex-col');
@endphp

<div class="specialist-card relative rounded-xl shadow-md transition-all duration-300 hover:shadow-lg overflow-hidden transform hover:-translate-y-1 {{ $cardBg }} {{ $borderColor }} {{ $cardClasses }} border">
    {{-- صورة المتخصص --}}
    <div class="{{ $mode === 'horizontal' ? 'w-1/3' : ($mode === 'compact' ? 'h-28' : 'h-40') }} relative overflow-hidden">
        <div class="relative w-full h-full">
            <img src="{{ asset($getImage()) }}" 
                 alt="{{ $getName() }}" 
                 class="w-full h-full object-cover">
            
            {{-- علامة الحالة (متصل/غير متصل) --}}
            @if(isset($specialist['is_online']) || (isset($specialist->is_online)))
                @php
                    $isOnline = is_array($specialist) ? ($specialist['is_online'] ?? false) : ($specialist->is_online ?? false);
                @endphp
                <div class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'left-2' : 'right-2' }} w-3 h-3 rounded-full {{ $isOnline ? 'bg-green-500' : 'bg-gray-400' }}">
                    <span class="ping absolute inline-flex h-full w-full rounded-full {{ $isOnline ? 'bg-green-400' : 'bg-gray-300' }} opacity-75"></span>
                </div>
            @endif
        </div>
    </div>
    
    {{-- معلومات المتخصص --}}
    <div class="{{ $mode === 'horizontal' ? 'w-2/3' : 'w-full' }} p-4">
        {{-- الاسم والتخصص --}}
        <div class="mb-2">
            <h3 class="font-semibold {{ $textColor }} text-lg">{{ $getName() }}</h3>
            <p class="{{ $subtleTextColor }} text-sm">{{ $getSpecialty() }}</p>
        </div>
        
        {{-- التقييم --}}
        <div class="flex items-center mb-3">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $getRating() ? $ratingColor : 'text-gray-300' }} text-sm"></i>
            @endfor
            
            @if(isset($specialist['reviews_count']) || isset($specialist->reviews_count))
                @php
                    $reviewsCount = is_array($specialist) ? ($specialist['reviews_count'] ?? 0) : ($specialist->reviews_count ?? 0);
                @endphp
                <span class="{{ $subtleTextColor }} text-xs ms-2">({{ $reviewsCount }})</span>
            @endif
        </div>
        
        {{-- معلومات إضافية (تظهر فقط في النمط الكامل أو الأفقي) --}}
        @if($mode !== 'compact')
            <div class="grid grid-cols-2 gap-2 mb-4 text-xs {{ $subtleTextColor }}">
                {{-- الخبرة --}}
                @if(isset($specialist['experience']) || isset($specialist->experience))
                    @php
                        $experience = is_array($specialist) ? ($specialist['experience'] ?? 0) : ($specialist->experience ?? 0);
                    @endphp
                    <div class="flex items-center">
                        <i class="far fa-calendar-alt me-1 text-purple-500"></i>
                        <span>{{ $experience }} {{ app()->getLocale() == 'ar' ? 'سنوات خبرة' : 'Years Exp.' }}</span>
                    </div>
                @endif
                
                {{-- عدد الجلسات --}}
                @if(isset($specialist['sessions_count']) || isset($specialist->sessions_count))
                    @php
                        $sessions = is_array($specialist) ? ($specialist['sessions_count'] ?? 0) : ($specialist->sessions_count ?? 0);
                    @endphp
                    <div class="flex items-center">
                        <i class="far fa-comments me-1 text-indigo-500"></i>
                        <span>{{ $sessions }}+ {{ app()->getLocale() == 'ar' ? 'جلسة' : 'Sessions' }}</span>
                    </div>
                @endif
                
                {{-- السعر --}}
                @if(isset($specialist['price']) || isset($specialist->price))
                    @php
                        $price = is_array($specialist) ? ($specialist['price'] ?? 0) : ($specialist->price ?? 0);
                    @endphp
                    <div class="flex items-center">
                        <i class="fas fa-tag me-1 text-pink-500"></i>
                        <span>{{ $price }} {{ app()->getLocale() == 'ar' ? 'ريال' : 'SAR' }}</span>
                    </div>
                @endif
            </div>
        @endif
        
        {{-- أزرار العمل --}}
        <div class="mt-auto pt-2 {{ $mode === 'compact' ? 'flex justify-center' : '' }}">
            <a href="{{ $getProfileUrl() }}" class="{{ $mode === 'compact' ? 'text-sm px-3 py-1.5' : 'text-sm px-4 py-2' }} inline-block rounded-lg {{ $ctaBgClass }} text-white font-medium hover:shadow-md transition-all transform active:scale-95">
                <i class="far fa-calendar-check me-1"></i> {{ $ctaText }}
            </a>
        </div>
    </div>
</div>