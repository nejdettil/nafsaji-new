<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index()
    {
        // فرض اللغة الصحيحة بناءً على جلسة المستخدم
        if (session('locale') === 'en') {
            app()->setLocale('en');
        } elseif (session('locale') === 'ar') {
            app()->setLocale('ar');
        }
        
        // جلب بيانات المتخصصين من قاعدة البيانات أو إنشاء بيانات وهمية إذا كانت الصفحة فارغة
        $dbSpecialists = Specialist::where('is_active', true)->get();
        
        if ($dbSpecialists->count() > 0) {
            $specialists = $dbSpecialists;
        } else {
            // إنشاء بيانات وهمية للعرض والاختبار
            $specialists = collect();
            
            // إضافة 9 متخصصين وهميين
            for ($i = 1; $i <= 9; $i++) {
                $name = app()->getLocale() == 'ar' ? 'د. أحمد محمد ' . $i : 'Dr. John Smith ' . $i;
                $specialists->push([
                    'id' => $i,
                    'name' => $name,
                    'specialty' => app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist',
                    'rating' => rand(3, 5),
                    'reviews_count' => rand(10, 50),
                    'experience' => rand(3, 15),
                    'sessions_count' => rand(100, 500),
                    'image' => 'https://randomuser.me/api/portraits/' . (rand(0, 1) ? 'men' : 'women') . '/' . rand(1, 99) . '.jpg',
                    'price' => rand(100, 300),
                    'availability' => rand(0, 1) ? 'متاح الآن' : 'متاح غدًا',
                    'is_online' => rand(0, 1),
                    'is_featured' => rand(0, 1),
                    'is_verified' => rand(0, 1),
                ]);
            }
        }
        
        // عمل صفحات للبيانات
        $perPage = 12;
        $page = request('page', 1);
        $pagedData = collect($specialists)->slice(($page - 1) * $perPage, $perPage)->all();
        $specialists = new \Illuminate\Pagination\LengthAwarePaginator($pagedData, count($specialists), $perPage, $page);
        $specialists->withPath(request()->url());

        return view('pages.specialists', [
            'specialists' => $specialists
        ]);
    }

    public function show($id)
    {
        $specialist = Specialist::findOrFail($id);
        
        return view('specialists.show', [
            'specialist' => $specialist
        ]);
    }
}
