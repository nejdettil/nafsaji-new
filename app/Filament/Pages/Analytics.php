<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Contact;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class Analytics extends Page
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'إحصائيات الموقع';
    protected static ?string $title = 'إحصائيات الموقع';
    protected static ?string $navigationGroup = 'نظرة عامة';
    protected static ?int $navigationSort = -1;
    
    public $startDate;
    public $endDate;
    
    // تعريف خصائص متوافقة مع Livewire
    public $usersDates = [];
    public $usersValues = [];
    public $specialistsDates = [];
    public $specialistsValues = [];
    public $bookingsDates = [];
    public $bookingsValues = [];
    public $revenueDates = [];
    public $revenueValues = [];
    public $servicesLabels = [];
    public $servicesData = [];
    
    // طرق getter للحصول على البيانات بطريقة متوافقة مع Livewire
    public function getUsersChartData()
    {
        return [
            'dates' => $this->usersDates,
            'values' => $this->usersValues,
        ];
    }
    
    public function getSpecialistsChartData()
    {
        return [
            'dates' => $this->specialistsDates,
            'values' => $this->specialistsValues,
        ];
    }
    
    public function getBookingsChartData()
    {
        return [
            'dates' => $this->bookingsDates,
            'values' => $this->bookingsValues,
        ];
    }
    
    public function getRevenueChartData()
    {
        return [
            'dates' => $this->revenueDates,
            'values' => $this->revenueValues,
        ];
    }
    
    public function getServicesChartData()
    {
        return [
            'labels' => $this->servicesLabels,
            'data' => $this->servicesData
        ];
    }
    
    protected ?string $maxContentWidth = MaxWidth::Full->value;
    protected static string $view = 'filament.pages.analytics';
    
    public function mount(): void
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        
        $this->form->fill([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
        
        $this->loadChartData();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('نطاق الإحصائيات')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('startDate')
                                    ->label('من تاريخ')
                                    ->required()
                                    ->default(fn () => Carbon::now()->subDays(30)->format('Y-m-d')),
                                DatePicker::make('endDate')
                                    ->label('إلى تاريخ')
                                    ->required()
                                    ->default(fn () => Carbon::now()->format('Y-m-d')),
                                \Filament\Forms\Components\Actions::make([
                                    \Filament\Forms\Components\Actions\Action::make('applyDateFilter')
                                        ->label('تحديث الإحصائيات')
                                        ->icon('heroicon-o-arrow-path')
                                        ->action(function (array $data) {
                                            $this->startDate = $data['startDate'];
                                            $this->endDate = $data['endDate'];
                                            
                                            $this->loadChartData();
                                        }),
                                ])
                                ->alignment(\Filament\Support\Enums\Alignment::Center)
                                ->verticalAlignment(\Filament\Support\Enums\VerticalAlignment::Center),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }
    
    public function loadChartData(): void
    {
        try {
            // بيانات إحصائيات المستخدمين
            $usersDataCollection = Trend::model(User::class)
                ->between(
                    start: Carbon::parse($this->startDate),
                    end: Carbon::parse($this->endDate),
                )
                ->perDay()
                ->count();
                
            // تحويل إلى مصفوفات متوافقة مع Livewire
            $this->usersDates = $usersDataCollection->map(fn ($value) => $value->date)->toArray();
            $this->usersValues = $usersDataCollection->map(fn ($value) => $value->aggregate)->toArray();
                
            // بيانات إحصائيات المختصين
            $specialistsDataCollection = Trend::model(Specialist::class)
                ->between(
                    start: Carbon::parse($this->startDate),
                    end: Carbon::parse($this->endDate),
                )
                ->perDay()
                ->count();
                
            // تحويل إلى مصفوفات متوافقة مع Livewire
            $this->specialistsDates = $specialistsDataCollection->map(fn ($value) => $value->date)->toArray();
            $this->specialistsValues = $specialistsDataCollection->map(fn ($value) => $value->aggregate)->toArray();
                
            // بيانات إحصائيات الحجوزات
            $bookingsDataCollection = Trend::model(Booking::class)
                ->between(
                    start: Carbon::parse($this->startDate),
                    end: Carbon::parse($this->endDate),
                )
                ->perDay()
                ->count();
                
            // تحويل إلى مصفوفات متوافقة مع Livewire
            $this->bookingsDates = $bookingsDataCollection->map(fn ($value) => $value->date)->toArray();
            $this->bookingsValues = $bookingsDataCollection->map(fn ($value) => $value->aggregate)->toArray();
        } catch (\Exception $e) {
            // إنشاء بيانات فارغة
            $startDate = Carbon::parse($this->startDate)->startOfDay();
            $endDate = Carbon::parse($this->endDate)->endOfDay();
            $dateRange = $startDate->daysUntil($endDate);
            
            $emptyDates = [];
            $emptyValues = [];
            
            foreach ($dateRange as $date) {
                $emptyDates[] = $date->format('Y-m-d');
                $emptyValues[] = 0;
            }
            
            $this->usersDates = $emptyDates;
            $this->usersValues = $emptyValues;
            $this->specialistsDates = $emptyDates;
            $this->specialistsValues = $emptyValues;
            $this->bookingsDates = $emptyDates;
            $this->bookingsValues = $emptyValues;
        }
            
        // بيانات إحصائيات الإيرادات - باستخدام طريقة مباشرة بدلاً من Trend
        try {
            // الحصول على نطاق التواريخ للرسم البياني
            $startDate = Carbon::parse($this->startDate)->startOfDay();
            $endDate = Carbon::parse($this->endDate)->endOfDay();
            $dateRange = $startDate->daysUntil($endDate);
            
            // إنشاء مصفوفة تواريخ فارغة
            $datesArray = [];
            foreach ($dateRange as $date) {
                $datesArray[$date->format('Y-m-d')] = 0;
            }
            
            // الحصول على بيانات الإيرادات من قاعدة البيانات
            $revenueByDay = Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(price) as revenue')
                ->groupBy('date')
                ->pluck('revenue', 'date')
                ->toArray();
            
            // دمج البيانات مع مصفوفة التواريخ
            foreach ($revenueByDay as $date => $revenue) {
                $datesArray[$date] = (float) $revenue;
            }
            
            // تخزين البيانات
            $this->revenueDates = [];
            $this->revenueValues = [];
            foreach ($datesArray as $date => $revenue) {
                $this->revenueDates[] = $date;
                $this->revenueValues[] = (float) $revenue;
            }
        } catch (\Exception $e) {
            // في حالة حدوث أي خطأ، مجموعة فارغة
            $this->revenueDates = [];
            $this->revenueValues = [];
        }
            
        // بيانات إحصائيات الخدمات
        try {
            // التحقق من وجود جدول الخدمات ووجود بيانات فيه
            if (\Schema::hasTable('services') && \App\Models\Service::exists()) {
                // إحصائيات الخدمات الأكثر طلباً
                $services = Service::withCount(['bookings' => function ($query) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($this->startDate),
                        Carbon::parse($this->endDate),
                    ]);
                }])
                ->orderByDesc('bookings_count')
                ->limit(10)
                ->get();
                
                $this->servicesLabels = $services->pluck('name')->toArray();
                $this->servicesData = $services->pluck('bookings_count')->toArray();
            } else {
                // إذا لم توجد خدمات، نقدم بيانات فارغة
                $this->servicesLabels = [];
                $this->servicesData = [];
            }
        } catch (\Exception $e) {
            // في حالة حدوث أي خطأ، بيانات فارغة
            $this->servicesData = [
                'labels' => [],
                'data' => [],
            ];
        }
    }
}
