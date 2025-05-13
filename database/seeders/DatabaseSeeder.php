<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء بذور الأدوار لإنشاء المستخدمين الثلاثة (مدير، مختص، مستخدم عادي)
        $this->call([
            RoleUserSeeder::class,
        ]);
        
        // يمكن استدعاء بذور أخرى هنا مستقبلاً
    }
}
