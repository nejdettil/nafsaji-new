<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\PermissionsSeeder;

class SetupPermissions extends Command
{
    /**
     * اسم الأمر ووصفه
     *
     * @var string
     */
    protected $signature = 'nafsaji:setup-permissions {--force : تنفيذ الأمر حتى لو كانت هناك أدوار وصلاحيات موجودة}';

    /**
     * وصف الأمر
     *
     * @var string
     */
    protected $description = 'تهيئة الأدوار والصلاحيات الافتراضية لنظام نفسجي';

    /**
     * تنفيذ الأمر
     */
    public function handle()
    {
        $force = $this->option('force');
        
        if (!$force) {
            // التحقق من وجود أدوار أو صلاحيات
            $roleCount = \Spatie\Permission\Models\Role::count();
            $permissionCount = \Spatie\Permission\Models\Permission::count();
            
            if ($roleCount > 0 || $permissionCount > 0) {
                if (!$this->confirm('توجد أدوار أو صلاحيات في قاعدة البيانات بالفعل. هل ترغب في المتابعة؟ سيؤدي ذلك إلى إضافة أي أدوار أو صلاحيات جديدة.')) {
                    $this->info('تم إلغاء الأمر.');
                    return;
                }
            }
        }
        
        $this->info('جاري تهيئة الأدوار والصلاحيات...');
        
        // تنفيذ بذر البيانات
        $seeder = new PermissionsSeeder();
        $seeder->run();
        
        $this->info('تم تهيئة الأدوار والصلاحيات بنجاح!');
        $this->table(
            ['نوع البيانات', 'العدد'],
            [
                ['الأدوار', \Spatie\Permission\Models\Role::count()],
                ['الصلاحيات', \Spatie\Permission\Models\Permission::count()],
            ]
        );
        
        return Command::SUCCESS;
    }
}
