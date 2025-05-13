@props(['ctaLink' => route('specialists.index')])

<div class="hero-gradient rounded-xl overflow-hidden relative mb-12">
    <!-- طبقة التأثير البصري -->
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    
    <div class="container mx-auto px-4 py-12 lg:py-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <!-- المحتوى النصي -->
            <div class="text-white">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6 slide-up">
                    {{ app()->getLocale() == 'ar' 
                        ? 'رعاية نفسية متكاملة <span class="text-yellow-300">في متناول يديك</span>' 
                        : 'Complete psychological care <span class="text-yellow-300">at your fingertips</span>' 
                    }}
                </h1>
                
                <p class="text-lg opacity-90 mb-8 max-w-xl slide-up" style="animation-delay: 0.1s">
                    {{ app()->getLocale() == 'ar' 
                        ? 'احصل على استشارة متخصصة من خبراء الصحة النفسية في أي وقت ومن أي مكان. نوفر لك تجربة شخصية آمنة وسرية تناسب احتياجاتك.' 
                        : 'Get specialized consultation from mental health experts anytime, anywhere. We provide you with a safe and confidential personal experience that suits your needs.'
                    }}
                </p>
                
                <div class="flex flex-wrap gap-4 slide-up" style="animation-delay: 0.2s">
                    <a href="{{ $ctaLink }}" class="btn-primary px-8 py-3 text-center inline-block hover:scale-105 transform transition-transform">
                        {{ app()->getLocale() == 'ar' ? 'تواصل مع متخصص' : 'Talk to a specialist' }}
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} ml-2"></i>
                    </a>
                    
                    <a href="{{ route('services.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg inline-block backdrop-blur-sm transition-all">
                        {{ app()->getLocale() == 'ar' ? 'استكشف خدماتنا' : 'Explore our services' }}
                    </a>
                </div>
                
                <!-- إحصائيات سريعة -->
                <div class="grid grid-cols-3 gap-4 mt-10 slide-up" style="animation-delay: 0.3s">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-yellow-300">+500</div>
                        <div class="text-sm text-white opacity-80">{{ app()->getLocale() == 'ar' ? 'متخصص معتمد' : 'Certified Specialists' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-yellow-300">+10K</div>
                        <div class="text-sm text-white opacity-80">{{ app()->getLocale() == 'ar' ? 'جلسة ناجحة' : 'Successful Sessions' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-yellow-300">+20</div>
                        <div class="text-sm text-white opacity-80">{{ app()->getLocale() == 'ar' ? 'خدمة متنوعة' : 'Diverse Services' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- الصورة التوضيحية - تظهر فقط على الشاشات الكبيرة -->
            <div class="hidden lg:block relative">
                <div class="relative z-10 fade-in">
                    <img src="{{ asset('images/hero-illustration.png') }}" alt="Nafsaji Specialists" class="max-w-full rounded-lg shadow-2xl transform -rotate-2">
                </div>
                
                <!-- عناصر زخرفية -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-400 rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-60 h-60 bg-purple-600 rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s"></div>
            </div>
        </div>
        
        <!-- المزايا الرئيسية -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 slide-up" style="animation-delay: 0.4s">
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 hover:bg-opacity-20 transition-all">
                <div class="text-yellow-300 text-2xl mb-3">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="text-white text-xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'خصوصية تامة' : 'Complete Privacy' }}</h3>
                <p class="text-white text-opacity-80 text-sm">
                    {{ app()->getLocale() == 'ar' ? 'نضمن سرية وخصوصية بياناتك وجلساتك مع المتخصصين' : 'We ensure the confidentiality and privacy of your data and sessions with specialists' }}
                </p>
            </div>
            
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 hover:bg-opacity-20 transition-all">
                <div class="text-yellow-300 text-2xl mb-3">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="text-white text-xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'متخصصون معتمدون' : 'Certified Specialists' }}</h3>
                <p class="text-white text-opacity-80 text-sm">
                    {{ app()->getLocale() == 'ar' ? 'جميع المتخصصين معتمدون ومرخصون من جهات رسمية معترف بها' : 'All specialists are certified and licensed by recognized official bodies' }}
                </p>
            </div>
            
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 hover:bg-opacity-20 transition-all">
                <div class="text-yellow-300 text-2xl mb-3">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-white text-xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'دعم على مدار الساعة' : '24/7 Support' }}</h3>
                <p class="text-white text-opacity-80 text-sm">
                    {{ app()->getLocale() == 'ar' ? 'فريق الدعم متاح على مدار الساعة للرد على استفساراتك ومساعدتك' : 'Support team is available 24/7 to answer your inquiries and assist you' }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>
