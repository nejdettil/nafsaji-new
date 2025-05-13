<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SystemMonitor extends Page
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationLabel = 'مراقبة النظام';
    protected static ?string $title = 'مراقبة النظام';
    protected static ?string $navigationGroup = 'إدارة النظام';
    protected static ?int $navigationSort = 3;
    
    protected ?string $maxContentWidth = MaxWidth::Full->value;
    protected static string $view = 'filament.pages.system-monitor';
    
    public $systemInfo = [];
    public $phpInfo = [];
    public $databaseInfo = [];
    public $cacheInfo = [];
    public $diskSpace = [];
    
    public function mount(): void
    {
        $this->loadSystemInfo();
    }
    
    public function loadSystemInfo(): void
    {
        // معلومات النظام
        $this->systemInfo = [
            'نظام التشغيل' => PHP_OS,
            'إصدار PHP' => PHP_VERSION,
            'إصدار Laravel' => app()->version(),
            'وقت الخادم' => now()->format('Y-m-d H:i:s'),
            'المنطقة الزمنية' => config('app.timezone'),
            'اللغة الافتراضية' => config('app.locale'),
            'البيئة' => app()->environment(),
            'وضع التصحيح' => config('app.debug') ? 'مفعل' : 'معطل',
            'وضع الصيانة' => app()->isDownForMaintenance() ? 'مفعل' : 'معطل',
        ];
        
        // معلومات PHP
        $this->phpInfo = [
            'حد الذاكرة' => ini_get('memory_limit'),
            'الحد الأقصى لوقت التنفيذ' => ini_get('max_execution_time') . ' ثانية',
            'الحد الأقصى لحجم الملف المرفوع' => ini_get('upload_max_filesize'),
            'الحد الأقصى لحجم POST' => ini_get('post_max_size'),
            'الملحقات المفعلة' => implode(', ', array_slice(get_loaded_extensions(), 0, 10)) . '...',
        ];
        
        // معلومات قاعدة البيانات
        try {
            $dbVersion = DB::select('select version() as version')[0]->version;
            $dbConnection = config('database.default');
            $dbName = config("database.connections.{$dbConnection}.database");
            $dbCharset = config("database.connections.{$dbConnection}.charset");
            $tablesCount = count(DB::select('SHOW TABLES'));
            
            $this->databaseInfo = [
                'اتصال قاعدة البيانات' => $dbConnection,
                'اسم قاعدة البيانات' => $dbName,
                'إصدار قاعدة البيانات' => $dbVersion,
                'ترميز قاعدة البيانات' => $dbCharset,
                'عدد الجداول' => $tablesCount,
            ];
        } catch (\Exception $e) {
            $this->databaseInfo = [
                'حالة قاعدة البيانات' => 'خطأ في الاتصال: ' . $e->getMessage(),
            ];
        }
        
        // معلومات ذاكرة التخزين المؤقت
        $this->cacheInfo = [
            'نظام التخزين المؤقت' => config('cache.default'),
            'حالة التخزين المؤقت' => Cache::get('test_cache_key', fn() => Cache::put('test_cache_key', true, 1)) ? 'يعمل' : 'لا يعمل',
        ];
        
        // معلومات مساحة القرص
        $rootPath = base_path();
        $totalSpace = disk_total_space($rootPath);
        $freeSpace = disk_free_space($rootPath);
        $usedSpace = $totalSpace - $freeSpace;
        $usedPercent = round(($usedSpace / $totalSpace) * 100, 2);
        
        $this->diskSpace = [
            'إجمالي المساحة' => $this->formatBytes($totalSpace),
            'المساحة المستخدمة' => $this->formatBytes($usedSpace),
            'المساحة المتاحة' => $this->formatBytes($freeSpace),
            'نسبة الاستخدام' => $usedPercent . '%',
        ];
    }
    
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    public function refreshInfo(): void
    {
        $this->loadSystemInfo();
        $this->notification()->success(
            'تم تحديث المعلومات',
            'تم تحديث معلومات النظام بنجاح'
        );
    }
}
