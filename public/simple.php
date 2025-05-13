<?php
// تفعيل إظهار الأخطاء
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// معلومات بسيطة
echo "<h1>مرحباً بك في نفسجي</h1>";
echo "<p>هذا اختبار بسيط للتأكد من عمل PHP</p>";
echo "<p>الوقت الحالي: " . date('Y-m-d H:i:s') . "</p>";
