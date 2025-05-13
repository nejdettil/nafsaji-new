<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Specialist;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopSpecialistsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'أفضل المتخصصين';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Specialist::select('specialists.*')
                    ->withCount(['bookings' => function (Builder $query) {
                        $query->whereIn('status', ['confirmed', 'completed']);
                    }])
                    ->withSum(['bookings' => function (Builder $query) {
                        $query->whereIn('status', ['confirmed', 'completed']);
                    }], 'price')
                    ->having('bookings_count', '>', 0)
                    ->orderByDesc('bookings_count')
                    ->limit(5);
            })
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(function ($record) {
                        // التحقق من وجود المستخدم قبل محاولة الوصول إلى الاسم
                        $name = $record->user ? $record->user->name : 'متخصص';
                        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('الاسم')
                    ->getStateUsing(fn ($record) => $record->user ? $record->user->name : 'غير محدد')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specialization')
                    ->label('التخصص')
                    ->getStateUsing(fn ($record) => $record->specialization ?: 'غير محدد'),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('عدد الحجوزات')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('bookings_sum_price')
                    ->label('إجمالي الإيرادات')
                    ->money('SAR')
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_profile')
                    ->label('عرض الملف')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Specialist $record): string => route('filament.admin.resources.specialists.view', $record)),
                Tables\Actions\Action::make('view_bookings')
                    ->label('عرض الحجوزات')
                    ->icon('heroicon-o-calendar')
                    ->url(function (Specialist $record) {
                        return route('filament.admin.resources.bookings.index', [
                            'tableFilters[specialist][value]' => $record->id,
                        ]);
                    }),
            ])
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateHeading('لا يوجد متخصصون نشطون')
            ->emptyStateDescription('سيتم عرض المتخصصين الأكثر نشاطًا هنا عند توفر بيانات الحجوزات.');
    }
}
