<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --font-family: 'Cairo', sans-serif;
    }
    
    body {
        font-family: 'Cairo', sans-serif !important;
    }
    
    /* تحسين أحجام الخطوط للنسخة العربية */
    h1, h2, h3, .text-2xl, .text-xl, .text-lg {
        font-weight: 700 !important;
    }
    
    /* تحسين حجم الخط في الأزرار */
    button, .fi-btn {
        font-weight: 600 !important;
    }
    
    /* تحسين المسافات بين العناصر */
    .fi-sidebar-nav, .fi-main {
        gap: 1rem !important;
    }
    
    /* تحسين شكل البطاقات */
    .fi-card {
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        transition: all 0.3s ease !important;
    }
    
    .fi-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    
    /* تحسين شكل الجداول */
    .fi-ta-table {
        border-radius: 8px !important;
        overflow: hidden !important;
    }
    
    .fi-ta-header-cell {
        background-color: rgba(147, 51, 234, 0.05) !important;
        font-weight: 700 !important;
    }
    
    /* تحسين شكل النماذج */
    .fi-input, .fi-select, .fi-textarea {
        border-radius: 8px !important;
    }
    
    /* تحسين تباعد الأقسام */
    .fi-section {
        margin-bottom: 2rem !important;
    }
    
    /* تأثيرات الانتقال */
    .fi-sidebar-item, .fi-btn, .fi-dropdown-list-item {
        transition: all 0.2s ease-in-out !important;
    }
    
    /* تحسين الإحصائيات */
    .fi-widget {
        border-radius: 12px !important;
        overflow: hidden !important;
    }
</style>
