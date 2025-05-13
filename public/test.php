<?php

echo '<h1>Nafsaji Test Page</h1>';
echo '<p>PHP Version: ' . phpversion() . '</p>';
echo '<h2>Loaded Extensions</h2>';
echo '<pre>';
print_r(get_loaded_extensions());
echo '</pre>';

echo '<h2>Environment Variables</h2>';
echo '<pre>';
print_r($_ENV);
echo '</pre>';
// تمكين عرض جميع الأخطاء
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// طباعة معلومات عن الخادم
echo '<h1>اختبار PHP</h1>';
echo '<pre>';
echo 'PHP Version: ' . phpversion() . "\n";
echo 'Server: ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo 'Document Root: ' . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo '</pre>';

// تحقق مما إذا كان Laravel متاح
$laravelPath = __DIR__ . '/../vendor/laravel/framework/src/Illuminate/Foundation/Application.php';
echo '<p>هل Laravel موجود: ' . (file_exists($laravelPath) ? 'نعم' : 'لا') . '</p>';

// تحقق من حالة قاعدة البيانات
try {
    $dbConfig = include __DIR__ . '/../config/database.php';
    echo '<p>إعدادات قاعدة البيانات: ' . ($dbConfig ? 'موجودة' : 'غير موجودة') . '</p>';
    
    if (isset($dbConfig['connections']['mysql'])) {
        $mysqlConfig = $dbConfig['connections']['mysql'];
        echo '<p>تكوين MySQL: ';
        echo 'Host: ' . $mysqlConfig['host'] . ', ';
        echo 'Database: ' . $mysqlConfig['database'] . '</p>';
    }
} catch (Exception $e) {
    echo '<p>خطأ في قاعدة البيانات: ' . $e->getMessage() . '</p>';
}
