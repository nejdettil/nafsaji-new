@props(['hasNotifications' => false])

<div class="mobile-nav-container">
    <div class="mobile-nav">
        <!-- الرئيسية -->
        <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="mobile-nav-label">{{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}</div>
            <div class="nav-active-indicator"></div>
        </a>

        <!-- المتخصصين -->
        <a href="{{ route('specialists.index') }}" class="mobile-nav-item {{ request()->routeIs('specialists.*') ? 'active' : '' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="mobile-nav-label">{{ app()->getLocale() == 'ar' ? 'المتخصصين' : 'Specialists' }}</div>
            <div class="nav-active-indicator"></div>
        </a>

        <!-- زر الحجز المركزي -->
        <div class="mobile-nav-center">
            <a href="{{ route('mobile.booking') }}" class="mobile-nav-center-button">
                <i class="fas fa-calendar-check"></i>
            </a>
        </div>

        <!-- الخدمات -->
        <a href="{{ route('services.index') }}" class="mobile-nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="mobile-nav-label">{{ app()->getLocale() == 'ar' ? 'الخدمات' : 'Services' }}</div>
            <div class="nav-active-indicator"></div>
        </a>

        <!-- حسابي -->
        <a href="{{ route('profile.index', [], false) }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <div class="mobile-nav-icon">
                <i class="fas fa-user"></i>
                @if($hasNotifications)
                <div class="notification-badge active">1</div>
                @endif
            </div>
            <div class="mobile-nav-label">{{ app()->getLocale() == 'ar' ? 'حسابي' : 'Profile' }}</div>
            <div class="nav-active-indicator"></div>
        </a>
    </div>
</div>
