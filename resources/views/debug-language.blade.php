<?php
// فرض اللغة من الجلسة مباشرة
if (session('locale') === 'en') {
    \Illuminate\Support\Facades\App::setLocale('en');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>لغة التطبيق الحالية / Current Application Language</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .box {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .important {
            background-color: #ffe0e0;
            border-color: #ff0000;
            font-weight: bold;
        }
        .code {
            font-family: monospace;
            background: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
        }
        .item {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>صفحة تشخيص اللغة / Language Diagnostics Page</h1>
    
    <div class="box important">
        <h2>حالة اللغة الحالية / Current Language Status</h2>
        <div class="item" style="color: red; font-weight: bold;">
            <strong>Current App::getLocale():</strong> <span class="code">{{ App::getLocale() }}</span>
            <span style="margin-left: 10px;">← هذه هي اللغة المطبقة حالياً / This is the currently applied language</span>
        </div>
        <div class="item">
            <strong>Session 'locale':</strong> <span class="code">{{ Session::get('locale', 'not set') }}</span>
        </div>
        <div class="item">
            <strong>Cookie 'locale':</strong> <span class="code">{{ request()->cookie('locale') ?? 'not set' }}</span>
        </div>
        <div class="item">
            <strong>Cache 'app_locale':</strong> <span class="code">{{ Cache::get('app_locale') ?? 'not set' }}</span>
        </div>
        <div class="item">
            <strong>Config Locale:</strong> <span class="code">{{ config('app.locale') }}</span>
        </div>
        <div class="item">
            <strong>Fallback Locale:</strong> <span class="code">{{ config('app.fallback_locale') }}</span>
        </div>
        <div class="item">
            <strong>Supported Languages:</strong> <span class="code">{{ implode(', ', config('app.supported_languages', ['ar', 'en'])) }}</span>
        </div>
        <div class="item">
            <strong>HTML lang attribute:</strong> <span class="code">{{ str_replace('_', '-', App::getLocale()) }}</span>
        </div>
        <div class="item">
            <strong>HTML dir attribute:</strong> <span class="code">{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}</span>
        </div>
    </div>
    
    <div class="box">
        <h2>اختبار الترجمة / Translation Test</h2>
        <table>
            <tr>
                <th>Key</th>
                <th>Translation</th>
            </tr>
            <tr>
                <td>messages.home</td>
                <td>{{ __('messages.home') }}</td>
            </tr>
            <tr>
                <td>messages.specialists</td>
                <td>{{ __('messages.specialists') }}</td>
            </tr>
            <tr>
                <td>messages.services</td>
                <td>{{ __('messages.services') }}</td>
            </tr>
            <tr>
                <td>messages.contact_us</td>
                <td>{{ __('messages.contact_us') }}</td>
            </tr>
            <tr>
                <td>messages.login</td>
                <td>{{ __('messages.login') }}</td>
            </tr>
            <tr>
                <td>messages.register</td>
                <td>{{ __('messages.register') }}</td>
            </tr>
            <tr>
                <td>pages.home.title</td>
                <td>{{ __('pages.home.title') }}</td>
            </tr>
        </table>
    </div>
    
    <div class="box">
        <h2>روابط تغيير اللغة / Language Change Links</h2>
        <p><a href="/language/ar">تغيير إلى العربية / Change to Arabic</a></p>
        <p><a href="/language/en">تغيير إلى الإنجليزية / Change to English</a></p>
        <p><a href="/force-english">فرض اللغة الإنجليزية / Force English</a></p>
        <p><a href="/">العودة إلى الصفحة الرئيسية / Return to Homepage</a></p>
    </div>
</body>
</html>
