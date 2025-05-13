@props([
    'rating' => 0, 
    'maxRating' => 5, 
    'interactive' => false,
    'size' => 'md',
    'name' => 'rating',
    'color' => 'gold'
])

@php
    // تحديد حجم النجوم
    $sizeClass = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl'
    ][$size] ?? 'text-base';
    
    // تحديد ألوان النجوم
    $colorClass = [
        'gold' => 'text-yellow-400',
        'purple' => 'text-primary-500',
        'blue' => 'text-blue-500'
    ][$color] ?? 'text-yellow-400';
    
    // تحويل التقييم إلى رقم
    $ratingValue = (float) $rating;
@endphp

<div 
    x-data="{ 
        rating: {{ $ratingValue }}, 
        hoverRating: 0,
        maxRating: {{ $maxRating }},
        interactive: {{ $interactive ? 'true' : 'false' }},
        onMouseEnter(index) {
            if (!this.interactive) return;
            this.hoverRating = index;
        },
        onMouseLeave() {
            if (!this.interactive) return;
            this.hoverRating = 0;
        },
        onSaveRating(index) {
            if (!this.interactive) return;
            this.rating = index;
            // تحديث قيمة حقل النموذج المخفي
            this.$refs.ratingInput.value = index;
            // حدث مخصص لإعلام المكونات الأخرى بالتغيير
            this.$dispatch('rating-changed', { value: index });
        },
        getStarClass(index) {
            const activeRating = this.hoverRating || this.rating;
            
            if (index <= activeRating) {
                return 'fas fa-star {{ $colorClass }}';
            }
            
            if (index - 0.5 <= activeRating) {
                return 'fas fa-star-half-alt {{ $colorClass }}';
            }
            
            return 'far fa-star text-gray-300';
        }
    }"
    class="star-rating inline-flex {{ $interactive ? 'cursor-pointer' : '' }}"
>
    @if($interactive)
        <input type="hidden" name="{{ $name }}" x-ref="ratingInput" :value="rating">
    @endif
    
    <template x-for="i in maxRating">
        <span 
            :class="getStarClass(i)"
            class="{{ $sizeClass }} transition-transform duration-100 mx-0.5"
            :style="interactive && 'transform: scale(' + (hoverRating === i ? 1.2 : 1) + ')'"
            @mouseenter="onMouseEnter(i)"
            @mouseleave="onMouseLeave"
            @click="onSaveRating(i)"
        ></span>
    </template>
    
    @if($slot->isNotEmpty())
        <span class="ml-2 {{ $sizeClass === 'text-sm' ? 'text-xs' : 'text-sm' }} text-gray-600">
            {{ $slot }}
        </span>
    @endif
</div>
