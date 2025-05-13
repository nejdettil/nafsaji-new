<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialists = [
            [
                'name' => 'د. أحمد محمد',
                'email' => 'ahmed.mohamed@nafsaji.com',
                'phone' => '+966501234567',
                'bio' => 'استشاري علم نفس بخبرة 15 عامًا في العلاج النفسي والأسري',
                'speciality' => 'علم النفس الأسري',
                'avatar' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'د. سارة عبدالله',
                'email' => 'sarah.abdullah@nafsaji.com',
                'phone' => '+966502345678',
                'bio' => 'معالجة نفسية متخصصة في علاج القلق والاكتئاب',
                'speciality' => 'علاج القلق والاكتئاب',
                'avatar' => 'https://randomuser.me/api/portraits/women/1.jpg',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'د. محمد علي',
                'email' => 'mohamed.ali@nafsaji.com',
                'phone' => '+966503456789',
                'bio' => 'أخصائي علاج سلوكي معرفي مع خبرة في التعامل مع الأطفال والمراهقين',
                'speciality' => 'العلاج السلوكي المعرفي',
                'avatar' => 'https://randomuser.me/api/portraits/men/2.jpg',
                'is_featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($specialists as $specialistData) {
            \App\Models\Specialist::create($specialistData);
        }
    }
}
