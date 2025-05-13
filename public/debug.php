<?php
// تفعيل ظهور الأخطاء
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحقق من وجود المتطلبات الأساسية
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'openssl'];
$missingExtensions = [];

echo '<h1>مشخص موقع نفسجي</h1>';

// التحقق من امتدادات PHP
echo '<h2>امتدادات PHP:</h2>';
echo '<ul>';
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
        echo '<li style="color: red;">الامتداد ' . $ext . ' غير مثبت</li>';
    } else {
        echo '<li style="color: green;">الامتداد ' . $ext . ' مثبت</li>';
    }
}
echo '</ul>';

// التحقق من وجود ملفات Laravel المهمة
echo '<h2>ملفات Laravel:</h2>';
$laravelFiles = [
    '../vendor/autoload.php',
    '../bootstrap/app.php',
    '../routes/web.php',
    '../resources/views/welcome.blade.php',
    '../app/Http/Controllers/AdminController.php',
    '../app/Http/Controllers/BookingController.php',
    '../resources/views/layouts/app.blade.php'
];

echo '<ul>';
foreach ($laravelFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        echo '<li style="color: green;">الملف ' . $file . ' موجود</li>';
    } else {
        echo '<li style="color: red;">الملف ' . $file . ' غير موجود</li>';
    }
}
echo '</ul>';

// التحقق من حالة الترجمات
echo '<h2>ملفات الترجمة:</h2>';
$langPath = __DIR__ . '/../lang';
if (is_dir($langPath)) {
    $langs = scandir($langPath);
    echo '<ul>';
    foreach ($langs as $lang) {
        if ($lang !== '.' && $lang !== '..') {
            echo '<li>اللغة: ' . $lang . '</li>';
        }
    }
    echo '</ul>';
} else {
    echo '<p style="color: red;">مجلد الترجمات غير موجود!</p>';
}

// معلومات البيئة
echo '<h2>معلومات البيئة:</h2>';
echo '<ul>';
echo '<li>نسخة PHP: ' . phpversion() . '</li>';
echo '<li>المسار الحالي: ' . __DIR__ . '</li>';
echo '</ul>';

// التحقق من ملفات الواجهة
echo '<h2>ملفات الواجهة المهمة:</h2>';
$viewFiles = [
    '../resources/views/admin/dashboard.blade.php',
    '../resources/views/booking/create.blade.php',
    '../resources/views/layouts/admin.blade.php',
    '../public/js/form-validation.js'
];

echo '<ul>';
foreach ($viewFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        echo '<li style="color: green;">الملف ' . $file . ' موجود</li>';
    } else {
        echo '<li style="color: red;">الملف ' . $file . ' غير موجود</li>';
    }
}
echo '</ul>';

// النتيجة النهائية
echo '<h2>التشخيص النهائي:</h2>';
if (count($missingExtensions) > 0) {
    echo '<p style="color: red;">يوجد امتدادات PHP مفقودة. يرجى تثبيتها.</p>';
} else {
    echo '<p style="color: green;">تكوين PHP يبدو جيداً.</p>';
}

// اختبار الاتصال بقاعدة البيانات
echo '<h2>قاعدة البيانات:</h2>';
try {
    // محاولة قراءة ملف env.
    $envFile = __DIR__ . '/../.env';
    $dbConfig = [];
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                if (strpos($key, 'DB_') === 0) {
                    $dbConfig[trim($key)] = trim($value);
                }
            }
        }
    }
    
    echo '<p>محاولة الاتصال بقاعدة البيانات...</p>';
    if (isset($dbConfig['DB_CONNECTION']) && isset($dbConfig['DB_HOST']) && isset($dbConfig['DB_DATABASE'])) {
        echo '<p>نوع قاعدة البيانات: ' . $dbConfig['DB_CONNECTION'] . '</p>';
        echo '<p>مضيف قاعدة البيانات: ' . $dbConfig['DB_HOST'] . '</p>';
        echo '<p>اسم قاعدة البيانات: ' . $dbConfig['DB_DATABASE'] . '</p>';
    } else {
        echo '<p style="color: red;">لم يتم العثور على إعدادات قاعدة البيانات!</p>';
    }
} catch (Exception $e) {
    echo '<p style="color: red;">خطأ: ' . $e->getMessage() . '</p>';
}
