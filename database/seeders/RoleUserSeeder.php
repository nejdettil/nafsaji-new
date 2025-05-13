<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم مدير
        User::updateOrCreate(
            ['email' => 'admin@nafsaji.com'],
            [
                'name' => 'مدير النظام',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // إنشاء مستخدم مختص
        User::updateOrCreate(
            ['email' => 'specialist@nafsaji.com'],
            [
                'name' => 'د. أحمد المختص',
                'role' => 'specialist',
                'password' => Hash::make('password'),
            ]
        );

        // إنشاء مستخدم عادي
        User::updateOrCreate(
            ['email' => 'user@nafsaji.com'],
            [
                'name' => 'محمد المستخدم',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]
        );
    }
}
