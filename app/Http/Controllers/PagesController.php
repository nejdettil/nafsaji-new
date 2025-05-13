<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function specialists()
    {
        // TODO: في المستقبل، سيتم جلب المتخصصين من قاعدة البيانات
        $specialists = [
            [
                'name' => 'د. محمد أحمد',
                'specialty' => 'علم النفس العيادي',
                'description' => 'خبرة 10 سنوات في علاج القلق والاكتئاب'
            ],
            [
                'name' => 'د. سارة خالد',
                'specialty' => 'العلاج الأسري',
                'description' => 'متخصصة في الإرشاد الأسري وحل النزاعات'
            ]
        ];

        return view('pages.specialists', compact('specialists'));
    }

    public function services()
    {
        $services = [
            [
                'title' => __('pages.services.individual_counseling.title'),
                'description' => __('pages.services.individual_counseling.description')
            ],
            [
                'title' => __('pages.services.group_therapy.title'),
                'description' => __('pages.services.group_therapy.description')
            ],
            [
                'title' => __('pages.services.online_consultation.title'),
                'description' => __('pages.services.online_consultation.description')
            ]
        ];

        return view('pages.services', compact('services'));
    }

    public function booking()
    {
        return view('pages.booking');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
