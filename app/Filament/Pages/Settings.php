<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class Settings extends Page
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'الإعدادات';
    protected static ?string $title = 'إعدادات النظام';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 90;
    
    public $site_name;
    public $site_description;
    public $site_logo;
    public $primary_color;
    public $secondary_color;
    public $enable_booking;
    public $maintenance_mode;
    public $contact_email;
    public $contact_phone;
    public $social_facebook;
    public $social_twitter;
    public $social_instagram;
    public $booking_confirmation_text;
    public $terms_conditions;
    public $privacy_policy;
    public $about_us;
    
    protected static string $view = 'filament.pages.settings';
    
    public function mount(): void
    {
        $this->form->fill([
            'site_name' => setting('site_name', 'نفسجي'),
            'site_description' => setting('site_description', 'منصة العلاج النفسي عن بعد'),
            'site_logo' => setting('site_logo'),
            'primary_color' => setting('primary_color', '#9333ea'),
            'secondary_color' => setting('secondary_color', '#6366f1'),
            'enable_booking' => (bool) setting('enable_booking', true),
            'maintenance_mode' => (bool) setting('maintenance_mode', false),
            'contact_email' => setting('contact_email', 'info@nafsaji.com'),
            'contact_phone' => setting('contact_phone', '+966-000-0000'),
            'social_facebook' => setting('social_facebook'),
            'social_twitter' => setting('social_twitter'),
            'social_instagram' => setting('social_instagram'),
            'booking_confirmation_text' => setting('booking_confirmation_text'),
            'terms_conditions' => setting('terms_conditions'),
            'privacy_policy' => setting('privacy_policy'),
            'about_us' => setting('about_us'),
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('settings')
                    ->tabs([
                        Tab::make('عام')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('معلومات الموقع')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('اسم الموقع')
                                            ->required(),
                                        Textarea::make('site_description')
                                            ->label('وصف الموقع')
                                            ->rows(3),
                                        FileUpload::make('site_logo')
                                            ->label('شعار الموقع')
                                            ->image()
                                            ->directory('settings')
                                            ->maxSize(1024),
                                    ]),
                                
                                Section::make('الألوان')
                                    ->schema([
                                        ColorPicker::make('primary_color')
                                            ->label('اللون الرئيسي'),
                                        ColorPicker::make('secondary_color')
                                            ->label('اللون الثانوي'),
                                    ])->columns(2),
                                    
                                Section::make('إعدادات النظام')
                                    ->schema([
                                        Toggle::make('enable_booking')
                                            ->label('تفعيل الحجوزات')
                                            ->helperText('السماح للمستخدمين بإجراء حجوزات جديدة'),
                                        Toggle::make('maintenance_mode')
                                            ->label('وضع الصيانة')
                                            ->helperText('تفعيل وضع الصيانة وإغلاق الموقع مؤقتاً'),
                                    ])->columns(2),
                            ]),
                            
                        Tab::make('الاتصال')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Section::make('معلومات الاتصال')
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->label('البريد الإلكتروني')
                                            ->email(),
                                        TextInput::make('contact_phone')
                                            ->label('رقم الهاتف')
                                            ->tel(),
                                    ])->columns(2),
                                    
                                Section::make('وسائل التواصل الاجتماعي')
                                    ->schema([
                                        TextInput::make('social_facebook')
                                            ->label('فيسبوك')
                                            ->url()
                                            ->prefix('https://'),
                                        TextInput::make('social_twitter')
                                            ->label('تويتر / إكس')
                                            ->url()
                                            ->prefix('https://'),
                                        TextInput::make('social_instagram')
                                            ->label('انستجرام')
                                            ->url()
                                            ->prefix('https://'),
                                    ])->columns(3),
                            ]),
                            
                        Tab::make('المحتوى')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('محتوى الموقع')
                                    ->schema([
                                        Textarea::make('booking_confirmation_text')
                                            ->label('نص تأكيد الحجز')
                                            ->placeholder('النص الذي سيظهر بعد تأكيد الحجز')
                                            ->rows(3),
                                        Textarea::make('about_us')
                                            ->label('من نحن')
                                            ->rows(5),
                                        Textarea::make('terms_conditions')
                                            ->label('الشروط والأحكام')
                                            ->rows(5),
                                        Textarea::make('privacy_policy')
                                            ->label('سياسة الخصوصية')
                                            ->rows(5),
                                    ])->columns(1),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            setting([$key => $value]);
        }
        
        setting()->save();
        
        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }
}
