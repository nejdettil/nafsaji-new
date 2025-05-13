<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'جلسة استشارة نفسية',
                'description' => 'جلسة أولية لتقييم الحالة النفسية والتواصل الأولي',
                'price' => 250.00,
                'duration' => 60,
                'is_active' => true
            ],
            [
                'name' => 'علاج القلق والاكتئاب',
                'description' => 'برنامج علاجي متكامل للتعامل مع القلق والاكتئاب',
                'price' => 500.00,
                'duration' => 90,
                'is_active' => true
            ],
            [
                'name' => 'الإرشاد الأسري',
                'description' => 'جلسات إرشادية للأسر لتعزيز التواصل وحل المشكلات',
                'price' => 350.00,
                'duration' => 75,
                'is_active' => true
            ]
        ];

        foreach ($services as $serviceData) {
            \App\Models\Service::create($serviceData);
        }
    }
}
