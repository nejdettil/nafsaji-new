<?php
// تمكين عرض الأخطاء بشكل كامل
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>أداة إصلاح موقع نفسجي</h1>";

// 1. التحقق من وجود وقابلية القراءة للمجلدات المهمة
$importantDirectories = [
    'storage' => '../storage',
    'bootstrap/cache' => '../bootstrap/cache',
    'resources/views' => '../resources/views',
    'public/js' => '../public/js',
    'public/css' => '../public/css'
];

echo "<h2>فحص المجلدات المهمة:</h2>";
echo "<ul>";
foreach ($importantDirectories as $name => $dir) {
    $fullPath = realpath(__DIR__ . '/' . $dir);
    echo "<li>$name: ";
    if (file_exists($fullPath)) {
        echo "موجود ";
        if (is_writable($fullPath)) {
            echo "<span style='color:green'>(قابل للكتابة)</span>";
        } else {
            echo "<span style='color:red'>(غير قابل للكتابة!)</span>";
            echo " → <span style='color:blue'>chmod -R 775 " . basename($fullPath) . "</span>";
        }
    } else {
        echo "<span style='color:red'>غير موجود!</span>";
    }
    echo "</li>";
}
echo "</ul>";

// 2. إنشاء ملف php_info.php
$phpinfoFile = __DIR__ . '/php_info.php';
file_put_contents($phpinfoFile, '<?php phpinfo();');
echo "<p>تم إنشاء <a href='php_info.php' target='_blank'>php_info.php</a> للتحقق من إعدادات PHP</p>";

// 3. الإجراءات المقترحة لإصلاح المشكلة
echo "<h2>الإجراءات المقترحة لإصلاح مشكلة الصفحة البيضاء:</h2>";
echo "<ol>";
echo "<li>تحديث ملف .env - قم بنسخ المحتوى أدناه وتحديث ملف .env الخاص بك:";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
APP_NAME=Nafsaji
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8020

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nafsaji_db
DB_USERNAME=root
DB_PASSWORD=
</pre></li>";

echo "<li>تنفيذ الأوامر التالية عبر الطرفية:";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
cd /Users/nejdettlimsani/CascadeProjects/nafsaji
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan key:generate
php artisan config:cache
</pre></li>";

echo "<li>إذا استمرت المشكلة، حاول إعادة تثبيت حزم Composer:";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
cd /Users/nejdettlimsani/CascadeProjects/nafsaji
composer dump-autoload
composer install --optimize-autoloader
</pre></li>";

echo "<li>التحقق من أذونات المجلدات:";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
chmod -R 775 storage
chmod -R 775 bootstrap/cache
</pre></li>";

echo "<li>مسح جميع ذاكرات التخزين المؤقت ثم إعادة تشغيل الخادم:";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
php artisan optimize:clear
php artisan optimize
php artisan serve
</pre></li>";
echo "</ol>";

// 4. مسار التصحيح السريع
echo "<h2>مسار التصحيح السريع:</h2>";
echo "<div style='background-color:#e8f4ff; padding:15px; border:1px solid #a0c4ff; margin:10px 0;'>";
echo "<p>لإيقاف وضع الإنتاج وتفعيل وضع التصحيح مباشرة، قم بتحديث ملف الإعدادات المخزن مؤقتاً:</p>";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
cp .env.example .env
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
</pre>";
echo "<p>ثم قم بتعديل ملف .env لتحديد البيئة كـ 'local' وتفعيل وضع التصحيح:</p>";
echo "<pre style='background-color:#f0f0f0; padding:10px; border:1px solid #ccc;'>
APP_ENV=local
APP_DEBUG=true
</pre>";
echo "</div>";

// 5. حالة البيئة والمعلومات المفيدة
echo "<h2>معلومات النظام:</h2>";
echo "<ul>";
echo "<li>نسخة PHP: " . phpversion() . "</li>";
echo "<li>المسار الحالي: " . __DIR__ . "</li>";

// تحقق من حالة الذاكرة المؤقتة
$cachePath = realpath(__DIR__ . '/../bootstrap/cache/config.php');
if (file_exists($cachePath)) {
    echo "<li>ملف ذاكرة التخزين المؤقت للإعدادات: <span style='color:orange'>موجود (قد يحتاج إلى مسح)</span></li>";
} else {
    echo "<li>ملف ذاكرة التخزين المؤقت للإعدادات: <span style='color:green'>غير موجود (جيد)</span></li>";
}

// تحقق من ملف .env
$envPath = realpath(__DIR__ . '/../.env');
if (file_exists($envPath)) {
    echo "<li>ملف .env: <span style='color:green'>موجود</span></li>";
} else {
    echo "<li>ملف .env: <span style='color:red'>غير موجود (مطلوب إنشاؤه)</span></li>";
}

echo "</ul>";

echo "<p style='margin-top:20px; font-weight:bold;'>بعد تنفيذ هذه الخطوات، يجب أن يعمل الموقع بشكل صحيح. إذا استمرت المشكلة، فقد تحتاج إلى فحص سجل الأخطاء أو استشارة مطور متخصص.</p>";
