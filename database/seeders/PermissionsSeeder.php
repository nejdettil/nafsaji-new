<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إعادة ضبط ذاكرة التخزين المؤقت
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات
        $permissions = [
            // صلاحيات النظام
            'admin.access' => 'دخول لوحة الإدارة',
            
            // صلاحيات المستخدمين
            'users.view' => 'عرض المستخدمين',
            'users.create' => 'إضافة مستخدمين',
            'users.edit' => 'تعديل المستخدمين',
            'users.delete' => 'حذف المستخدمين',
            
            // صلاحيات المختصين
            'specialists.view' => 'عرض المختصين',
            'specialists.create' => 'إضافة مختصين',
            'specialists.edit' => 'تعديل المختصين',
            'specialists.delete' => 'حذف المختصين',
            
            // صلاحيات الخدمات
            'services.view' => 'عرض الخدمات',
            'services.create' => 'إضافة خدمات',
            'services.edit' => 'تعديل الخدمات',
            'services.delete' => 'حذف الخدمات',
            
            // صلاحيات الحجوزات
            'bookings.view' => 'عرض الحجوزات',
            'bookings.create' => 'إضافة حجوزات',
            'bookings.edit' => 'تعديل الحجوزات',
            'bookings.delete' => 'حذف الحجوزات',
            
            // صلاحيات الإشعارات
            'notifications.view' => 'عرض الإشعارات',
            'notifications.create' => 'إرسال إشعارات',
            
            // صلاحيات الإعدادات
            'settings.view' => 'عرض الإعدادات',
            'settings.edit' => 'تعديل الإعدادات',
        ];

        foreach ($permissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'description' => $description,
                'guard_name' => 'web'
            ]);
        }

        // إنشاء الأدوار مع الصلاحيات
        $roles = [
            'super_admin' => [
                'name' => 'مدير النظام',
                'description' => 'المدير المسؤول عن النظام بالكامل مع كل الصلاحيات',
                'permissions' => Permission::all(),
            ],
            'admin' => [
                'name' => 'مشرف',
                'description' => 'مشرف النظام مع معظم الصلاحيات',
                'permissions' => Permission::whereNotIn('name', [
                    'settings.edit',
                ])->get(),
            ],
            'specialist' => [
                'name' => 'مختص',
                'description' => 'مختص نفسي يقدم خدمات استشارية',
                'permissions' => Permission::whereIn('name', [
                    'admin.access',
                    'bookings.view',
                    'bookings.edit',
                ])->get(),
            ],
            'user' => [
                'name' => 'مستخدم',
                'description' => 'مستخدم عادي',
                'permissions' => [],
            ],
        ];

        foreach ($roles as $roleKey => $roleData) {
            $role = Role::create([
                'name' => $roleKey,
                'description' => $roleData['description'],
                'guard_name' => 'web',
            ]);
            
            if (!empty($roleData['permissions'])) {
                $role->syncPermissions($roleData['permissions']);
            }
        }

        // تعيين دور مدير النظام لأول مستخدم (عادة يكون المدير)
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->assignRole('super_admin');
        }
    }
}
