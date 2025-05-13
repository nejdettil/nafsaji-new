<?php

namespace App\Filament\UserResources;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
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
    protected static ?string $navigationLabel = 'حجوزاتي';
    protected static ?string $modelLabel = 'حجز';
    protected static ?string $pluralModelLabel = 'الحجوزات';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        // فقط حجوزات المستخدم الحالي
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('إنشاء حجز جديد')
                    ->schema([
                        Forms\Components\Select::make('specialist_id')
                            ->label('المختص')
                            ->options(Specialist::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('booking_date')
                            ->label('تاريخ الحجز')
                            ->required()
                            ->minDate(now()->addDay())
                            ->closeOnDateSelection(),
                        Forms\Components\TimePicker::make('booking_time')
                            ->label('وقت الحجز')
                            ->required()
                            ->minutesStep(30),
                        Forms\Components\Select::make('session_type')
                            ->label('نوع الجلسة')
                            ->options(Service::pluck('name', 'name'))
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('specialist.name')
                    ->label('المختص')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('cancel')
                    ->label('إلغاء الحجز')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (Booking $record) => $record->update(['status' => 'canceled']))
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record): bool => $record->status === 'pending'),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('booking_date', 'desc')
            ->emptyStateHeading('لا توجد حجوزات')
            ->emptyStateDescription('لم تقم بإجراء أية حجوزات بعد. يمكنك إنشاء حجز جديد من خلال الضغط على زر إنشاء حجز.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('إنشاء حجز جديد'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\UserResources\BookingResource\Pages\ListBookings::route('/'),
            'create' => \App\Filament\UserResources\BookingResource\Pages\CreateBooking::route('/create'),
            'view' => \App\Filament\UserResources\BookingResource\Pages\ViewBooking::route('/{record}'),
        ];
    }
}
