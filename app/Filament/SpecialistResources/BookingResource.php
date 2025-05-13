<?php

namespace App\Filament\SpecialistResources;

use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'الحجوزات';
    protected static ?string $modelLabel = 'حجز';
    protected static ?string $pluralModelLabel = 'الحجوزات';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        // فقط الحجوزات المرتبطة بالمختص الحالي
        return parent::getEloquentQuery()->where('specialist_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الحجز')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('حالة الحجز')
                            ->options([
                                'pending' => 'قيد الانتظار',
                                'confirmed' => 'مؤكد',
                                'canceled' => 'ملغي',
                                'completed' => 'مكتمل',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('specialist_notes')
                            ->label('ملاحظات المختص')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('تاريخ الحجز')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_time')
                    ->label('وقت الحجز')
                    ->time('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('session_type')
                    ->label('نوع الجلسة')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->sortable()
                    ->colors([
                        'danger' => 'canceled',
                        'warning' => 'pending',
                        'success' => fn ($state) => in_array($state, ['confirmed', 'completed']),
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'canceled' => 'ملغي',
                        'completed' => 'مكتمل',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'canceled' => 'ملغي',
                        'completed' => 'مكتمل',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_details')
                    ->label('عرض التفاصيل')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Booking $record): string => route('specialist.bookings.show', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('confirm')
                    ->label('تأكيد المحدد')
                    ->icon('heroicon-o-check')
                    ->action(fn (Builder $query) => $query->update(['status' => 'confirmed']))
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('cancel')
                    ->label('إلغاء المحدد')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn (Builder $query) => $query->update(['status' => 'canceled']))
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),
            ])
            ->defaultSort('booking_date', 'desc')
            ->emptyStateHeading('لا توجد حجوزات')
            ->emptyStateDescription('لا توجد حجوزات حالياً، ستظهر هنا عندما يقوم العملاء بإجراء حجز.');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\SpecialistResources\BookingResource\Pages\ListBookings::route('/'),
            'edit' => \App\Filament\SpecialistResources\BookingResource\Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
