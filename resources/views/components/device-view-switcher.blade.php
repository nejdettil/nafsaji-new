@props(['position' => 'bottom-left', 'textColor' => 'text-gray-500', 'bgColor' => 'bg-white', 'showLabel' => true])

<div class="{{ $position == 'bottom-left' ? 'fixed bottom-4 left-4' : ($position == 'bottom-right' ? 'fixed bottom-4 right-4' : ($position == 'top-left' ? 'fixed top-4 left-4' : 'fixed top-4 right-4')) }} z-50 device-view-switcher">
    @if (\App\Services\DeviceService::isMobile() && !\App\Services\DeviceService::isForceDesktopView() && isset($desktopViewUrl))
        {{-- زر التبديل إلى عرض سطح المكتب عندما نكون في عرض الجوال --}}
        <a href="{{ $desktopViewUrl }}" class="flex items-center gap-1 p-2 px-3 rounded-full shadow-lg {{ $bgColor }} {{ $textColor }} transition-all transform hover:shadow-xl">
            <i class="fas fa-desktop text-base"></i>
            @if($showLabel)
                <span class="text-sm">{{ app()->getLocale() == 'ar' ? 'عرض سطح المكتب' : 'Desktop View' }}</span>
            @endif
        </a>
    @elseif((\App\Services\DeviceService::isMobile() && \App\Services\DeviceService::isForceDesktopView()) || !\App\Services\DeviceService::isMobile())
        {{-- زر التبديل إلى عرض الجوال عندما نكون في عرض سطح المكتب أو عندما تم فرض عرض سطح المكتب على الجوال --}}
        @if(isset($mobileViewUrl))
            <a href="{{ $mobileViewUrl }}" class="flex items-center gap-1 p-2 px-3 rounded-full shadow-lg {{ $bgColor }} {{ $textColor }} transition-all transform hover:shadow-xl">
                <i class="fas fa-mobile-alt text-base"></i>
                @if($showLabel)
                    <span class="text-sm">{{ app()->getLocale() == 'ar' ? 'عرض الجوال' : 'Mobile View' }}</span>
                @endif
            </a>
        @endif
    @endif
</div>