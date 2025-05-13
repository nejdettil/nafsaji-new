<!-- أيقونات مخصصة للقائمة الجانبية -->
<style>
    /* تحسين حجم وتباعد الأيقونات */
    .fi-sidebar-nav-item {
        margin-bottom: 0.5rem !important;
        transition: all 0.2s ease-in-out !important;
    }
    
    /* تأثير حركي عند تمرير المؤشر */
    .fi-sidebar-nav-item:hover {
        transform: translateX(-2px) !important;
    }
    
    /* تحسين أيقونات التنقل */
    .fi-sidebar-nav-item-active {
        position: relative !important;
        overflow: hidden !important;
    }
    
    /* تأثير إضافي للعنصر النشط */
    .fi-sidebar-nav-item-active::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(to bottom, #9333ea, #6366f1);
        border-radius: 4px;
    }
    
    /* تنعيم حواف العناصر */
    .fi-sidebar-group {
        border-radius: 1rem !important;
        padding: 0.5rem !important;
    }
    
    /* تحسين عناوين المجموعات */
    .fi-sidebar-group-label {
        font-weight: 700 !important;
        font-size: 0.875rem !important;
        margin-bottom: 0.5rem !important;
        color: #9333ea !important;
    }
    
    /* جعل الانتقال بين النوافذ أكثر سلاسة */
    .fi-main {
        transition: all 0.3s ease-in-out !important;
    }
</style>
