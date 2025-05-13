@extends('layouts.app')

@section('title', 'المتخصصين')

@section('styles')
<style>
    /* أنماط مخصصة للصفحة */
    .specialist-card {
        transition: all 0.3s ease;
    }
    
    .specialist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .hero-section {
        background: linear-gradient(135deg, #8a4baf 0%, #4c1d95 100%);
    }
    
    .filter-panel {
        background: linear-gradient(to right, #f9fafb, #f3f4f6);
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="hero-section py-16 px-4 text-white">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">{{ app()->getLocale() == 'ar' ? 'المتخصصون النفسيون' : 'Our Specialists' }}</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto mb-8">
                {{ app()->getLocale() == 'ar' ? 'اختر المتخصص المناسب لاحتياجاتك من بين نخبة من أفضل المتخصصين والأطباء النفسيين' : 'Choose the right specialist for your needs from our elite team of mental health professionals' }}
            </p>
            
            <!-- Search Box -->
            <div class="max-w-md mx-auto bg-white rounded-full shadow-lg p-1 flex">
                <input type="text" class="flex-1 px-4 py-2 rounded-l-full focus:outline-none" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن متخصص...' : 'Search for a specialist...' }}">
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto py-12 px-4">
        <!-- Filter Panel -->
        <div class="filter-panel rounded-xl shadow-md p-5 mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ app()->getLocale() == 'ar' ? 'تصفية المتخصصين' : 'Filter Specialists' }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Specialty Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'التخصص' : 'Specialty' }}</label>
                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>{{ app()->getLocale() == 'ar' ? 'جميع التخصصات' : 'All Specialties' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'معالج نفسي' : 'Psychotherapist' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'استشاري نفسي' : 'Psychological Consultant' }}</option>
                    </select>
                </div>
                
                <!-- Experience Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'الخبرة' : 'Experience' }}</label>
                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>{{ app()->getLocale() == 'ar' ? 'جميع المستويات' : 'All Levels' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'أكثر من 5 سنوات' : 'More than 5 years' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'أكثر من 10 سنوات' : 'More than 10 years' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'أكثر من 15 سنة' : 'More than 15 years' }}</option>
                    </select>
                </div>
                
                <!-- Availability Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'التوفر' : 'Availability' }}</label>
                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>{{ app()->getLocale() == 'ar' ? 'جميع الأوقات' : 'Any Time' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'متاح اليوم' : 'Available Today' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'متاح هذا الأسبوع' : 'Available This Week' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'متاح للاستشارات الفورية' : 'Available for Immediate Consultation' }}</option>
                    </select>
                </div>
                
                <!-- Price Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}</label>
                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>{{ app()->getLocale() == 'ar' ? 'جميع الأسعار' : 'All Prices' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'أقل من 100' : 'Less than 100' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? '100 - 200' : '100 - 200' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'أكثر من 200' : 'More than 200' }}</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button class="bg-white border border-purple-500 text-purple-600 px-4 py-2 rounded-lg mr-2 hover:bg-purple-50">
                    {{ app()->getLocale() == 'ar' ? 'إعادة ضبط' : 'Reset' }}
                </button>
                <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    {{ app()->getLocale() == 'ar' ? 'تطبيق الفلتر' : 'Apply Filter' }}
                </button>
            </div>
        </div>
        
        <!-- Specialists Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for ($i = 1; $i <= 9; $i++)
            <div class="specialist-card bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Card Header -->
                <div class="h-32 bg-gradient-to-r from-purple-500 to-indigo-600 relative">
                    <!-- Tags -->
                    <div class="absolute top-2 right-2 space-x-1 rtl:space-x-reverse flex">
                        <span class="bg-white bg-opacity-90 text-purple-700 text-xs font-medium px-2 py-1 rounded-full">
                            {{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}
                        </span>
                        <span class="bg-green-50 bg-opacity-90 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                            {{ app()->getLocale() == 'ar' ? 'متاح الآن' : 'Available Now' }}
                        </span>
                    </div>
                    
                    <!-- Avatar -->
                    <div class="absolute -bottom-10 left-6 w-20 h-20 rounded-xl bg-white p-1">
                        <div class="w-full h-full rounded-lg bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                        </div>
                    </div>
                </div>
                
                <!-- Card Content -->
                <div class="pt-12 px-6 pb-4">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                {{ app()->getLocale() == 'ar' ? 'د. أحمد محمد ' . $i : 'Dr. John Smith ' . $i }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}
                            </p>
                        </div>
                        <div class="flex items-center bg-yellow-50 px-2 py-1 rounded-md">
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            <span class="font-medium">{{ 4 + ($i % 10) / 10 }}</span>
                            <span class="text-gray-500 text-sm ml-1">({{ 10 + $i * 3 }})</span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-gray-600 text-sm mt-3 mb-4">
                        {{ app()->getLocale() == 'ar' ? 'طبيب متخصص في علاج مشاكل الصحة النفسية، لديه خبرة في علاج الاكتئاب والقلق واضطرابات النوم.' : 'Specialized doctor in treating mental health issues with experience in treating depression, anxiety, and sleep disorders.' }}
                    </p>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                        <div class="bg-gray-50 rounded-lg p-2">
                            <div class="text-purple-700 font-bold">{{ 3 + $i % 10 }}</div>
                            <div class="text-gray-600 text-xs">{{ app()->getLocale() == 'ar' ? 'سنوات الخبرة' : 'Years Exp.' }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <div class="text-purple-700 font-bold">{{ 100 + $i * 20 }}</div>
                            <div class="text-gray-600 text-xs">{{ app()->getLocale() == 'ar' ? 'الجلسات' : 'Sessions' }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <div class="text-purple-700 font-bold">{{ 50 + $i * 10 }}</div>
                            <div class="text-gray-600 text-xs">{{ app()->getLocale() == 'ar' ? 'ريال/الساعة' : 'SAR/Hour' }}</div>
                        </div>
                    </div>
                    
                    <!-- Button -->
                    <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-2 rounded-lg hover:opacity-90 transition-opacity">
                        {{ app()->getLocale() == 'ar' ? 'حجز موعد' : 'Book Appointment' }}
                    </button>
                </div>
            </div>
            @endfor
        </div>
        
        <!-- Pagination -->
        <div class="mt-10 flex justify-center">
            <div class="inline-flex shadow-md rounded-lg overflow-hidden">
                <a href="#" class="px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 border-r">&laquo;</a>
                <a href="#" class="px-4 py-2 bg-purple-600 text-white hover:bg-purple-700">1</a>
                <a href="#" class="px-4 py-2 bg-white text-gray-700 hover:bg-gray-50">2</a>
                <a href="#" class="px-4 py-2 bg-white text-gray-700 hover:bg-gray-50">3</a>
                <a href="#" class="px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 border-l">&raquo;</a>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="bg-white py-16 px-4">
        <div class="container mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">{{ app()->getLocale() == 'ar' ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ app()->getLocale() == 'ar' ? 'إليك بعض الأسئلة الشائعة حول خدماتنا والمتخصصين لدينا' : 'Here are some common questions about our services and specialists' }}
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="mb-6 bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{ app()->getLocale() == 'ar' ? 'كيف يمكنني اختيار المتخصص المناسب؟' : 'How do I choose the right specialist?' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ app()->getLocale() == 'ar' ? 'يمكنك اختيار المتخصص بناءً على تخصصه وخبرته والتقييمات التي حصل عليها من المستخدمين السابقين. يمكنك أيضًا استخدام خدمة المساعدة في الاختيار للحصول على توصية مخصصة.' : 'You can choose a specialist based on their specialty, experience, and ratings from previous users. You can also use our selection assistance service for a personalized recommendation.' }}
                    </p>
                </div>
                
                <div class="mb-6 bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{ app()->getLocale() == 'ar' ? 'كيف يتم تحديد أسعار الجلسات؟' : 'How are session prices determined?' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ app()->getLocale() == 'ar' ? 'تختلف أسعار الجلسات بناءً على تخصص المتخصص وخبرته ونوع الجلسة (عبر الإنترنت أو وجهاً لوجه). يمكنك رؤية سعر كل جلسة على صفحة المتخصص.' : 'Session prices vary based on the specialist\'s expertise, experience, and session type (online or in-person). You can see the price for each session on the specialist\'s page.' }}
                    </p>
                </div>
                
                <div class="mb-6 bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{ app()->getLocale() == 'ar' ? 'هل يمكنني إلغاء موعدي؟' : 'Can I cancel my appointment?' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ app()->getLocale() == 'ar' ? 'نعم، يمكنك إلغاء موعدك قبل 24 ساعة من وقت الجلسة للحصول على استرداد كامل. الإلغاء قبل 12 ساعة يمنحك استردادًا جزئيًا بنسبة 50٪.' : 'Yes, you can cancel your appointment 24 hours before the session time for a full refund. Cancellation before 12 hours gives you a 50% partial refund.' }}
                    </p>
                </div>
            </div>
            
            <!-- Help Box -->
            <div class="mt-12 max-w-3xl mx-auto bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-8 text-white text-center shadow-xl">
                <h3 class="text-2xl font-bold mb-4">{{ app()->getLocale() == 'ar' ? 'تحتاج مساعدة في الاختيار؟' : 'Need Help Choosing?' }}</h3>
                <p class="mb-6 opacity-90">
                    {{ app()->getLocale() == 'ar' ? 'دعنا نساعدك في العثور على المتخصص المناسب لاحتياجاتك الفريدة' : 'Let us help you find the right specialist for your unique needs' }}
                </p>
                <div class="flex justify-center space-x-4 rtl:space-x-reverse">
                    <a href="#" class="bg-white text-purple-700 px-5 py-2 rounded-lg hover:bg-gray-100 font-medium">
                        {{ app()->getLocale() == 'ar' ? 'تحدث مع مستشار' : 'Talk to an Advisor' }}
                    </a>
                    <a href="#" class="bg-purple-700 bg-opacity-30 text-white border border-purple-300 px-5 py-2 rounded-lg hover:bg-opacity-40 font-medium">
                        {{ app()->getLocale() == 'ar' ? 'اتصل بنا' : 'Contact Us' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
