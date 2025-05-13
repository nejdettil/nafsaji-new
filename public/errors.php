<?php
// تفعيل عرض الأخطاء بشكل قوي
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>صفحة فحص الأخطاء</h1>";

// التحقق من ملفات Laravel الأساسية
$requiredFiles = [
    '../vendor/autoload.php',
    '../bootstrap/app.php',
    '../config/app.php',
    '../routes/web.php'
];

foreach ($requiredFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    echo "<p>الملف: $file - ";
    if (file_exists($fullPath)) {
        echo "<span style='color:green'>موجود</span></p>";
        
        // اختبار تضمين الملف
        echo "<p>محاولة تضمين $file: ";
        try {
            include_once $fullPath;
            echo "<span style='color:green'>تم التضمين بنجاح</span></p>";
        } catch (Exception $e) {
            echo "<span style='color:red'>فشل! الخطأ: " . $e->getMessage() . "</span></p>";
        }
    } else {
        echo "<span style='color:red'>غير موجود!</span></p>";
    }
}

// التحقق من ملف .env
echo "<h2>ملف .env:</h2>";
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    echo "<p style='color:green'>ملف .env موجود</p>";
    
    // عرض بعض الإعدادات المهمة من ملف .env بطريقة آمنة
    $env = file_get_contents($envFile);
    $lines = explode("\n", $env);
    
    $safeSettings = ['APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST'];
    echo "<ul>";
    foreach ($lines as $line) {
        foreach ($safeSettings as $setting) {
            if (strpos($line, $setting . '=') === 0) {
                echo "<li>" . htmlspecialchars($line) . "</li>";
            }
        }
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>ملف .env غير موجود!</p>";
}

// التحقق من ملف الإعدادات
try {
    echo "<h2>تحميل ملف الإعدادات:</h2>";
    
    // محاولة تحميل ملف تكوين التطبيق بطريقة آمنة
    $configPath = __DIR__ . '/../config/app.php';
    if (file_exists($configPath)) {
        echo "<p style='color:green'>ملف app.php موجود</p>";
        
        // تحميل ملف التكوين بأمان
        $config = include $configPath;
        
        echo "<p>اسم التطبيق: " . (isset($config['name']) ? $config['name'] : 'غير محدد') . "</p>";
        echo "<p>بيئة التطبيق: " . (isset($config['env']) ? $config['env'] : 'غير محددة') . "</p>";
        echo "<p>وضع التصحيح: " . (isset($config['debug']) ? ($config['debug'] ? 'مفعل' : 'معطل') : 'غير محدد') . "</p>";
    } else {
        echo "<p style='color:red'>ملف app.php غير موجود!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>حدث خطأ أثناء تحميل ملف التكوين: " . $e->getMessage() . "</p>";
}

// نصائح لإصلاح المشكلة
echo "<h2>نصائح للإصلاح:</h2>";
echo "<ol>";
echo "<li>تأكد من وجود ملف .env وصحة محتوياته</li>";
echo "<li>تأكد من تنفيذ الأمر: php artisan config:clear</li>";
echo "<li>تأكد من تنفيذ الأمر: php artisan cache:clear</li>";
echo "<li>تأكد من صحة اتصال قاعدة البيانات</li>";
echo "<li>تأكد من أن مفتاح التطبيق (APP_KEY) تم إنشاؤه بشكل صحيح</li>";
echo "<li>تحقق من سجلات الخطأ في storage/logs/laravel.log</li>";
echo "</ol>";

echo "<h2>تفاصيل التكوين:</h2>";
echo "<pre>";
echo "نسخة PHP: " . phpversion() . "\n";
echo "المسار الحالي: " . __DIR__ . "\n";
echo "المسار الأصلي: " . realpath(__DIR__) . "\n";
echo "</pre>";
