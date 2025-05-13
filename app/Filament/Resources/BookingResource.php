<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'إدارة الحجوزات';
    protected static ?string $navigationLabel = 'الحجوزات';
    protected static ?string $modelLabel = 'حجز';
    protected static ?string $pluralModelLabel = 'الحجوزات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تفاصيل الحجز')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('المستخدم')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('specialist_id')
                            ->label('المختص')
                            ->relationship('specialist', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('service_id')
                            ->label('الخدمة')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('تاريخ الحجز')
                            ->required(),
                        Forms\Components\TimePicker::make('time')
                            ->label('وقت الحجز')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('حالة الحجز')
                            ->options([
                                'pending' => 'قيد الانتظار',
                                'confirmed' => 'مؤكد',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي',
                                'no-show' => 'لم يحضر',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->label('المدة (دقيقة)')
                            ->numeric()
                            ->default(60)
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('السعر')
                            ->numeric()
                            ->required(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('ملاحظات')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات العميل')
                            ->placeholder('ملاحظات العميل عند الحجز')
                            ->rows(3),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('ملاحظات الإدارة')
                            ->placeholder('ملاحظات خاصة بالإدارة لا تظهر للمستخدم')
                            ->rows(3),
                    ])->columns(2),
                    
                Forms\Components\Section::make('معلومات الدفع')
                    ->schema([
                        Forms\Components\Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'unpaid' => 'غير مدفوع',
                                'paid' => 'مدفوع',
                                'refunded' => 'مسترجع',
                                'partial' => 'مدفوع جزئياً',
                            ])
                            ->default('unpaid')
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('طريقة الدفع')
                            ->options([
                                'cash' => 'نقدي',
                                'bank_transfer' => 'تحويل بنكي',
                                'credit_card' => 'بطاقة ائتمانية',
                                'online' => 'دفع إلكتروني',
                            ]),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('رقم العملية')
                            ->placeholder('رقم مرجعي لعملية الدفع'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('رقم الحجز')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Booking $record) => route('filament.admin.resources.users.edit', ['record' => $record->user_id]))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('specialist.name')
                    ->label('المختص')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Booking $record) => route('filament.admin.resources.specialists.edit', ['record' => $record->specialist_id]))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('الخدمة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('الوقت')
                    ->time('h:i A')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'confirmed',
                        'primary' => 'completed',
                        'danger' => 'cancelled',
                        'warning' => 'no-show',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                        'no-show' => 'لم يحضر',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('حالة الدفع')
                    ->colors([
                        'gray' => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'partial',
                        'danger' => 'refunded',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid' => 'غير مدفوع',
                        'paid' => 'مدفوع',
                        'refunded' => 'مسترجع',
                        'partial' => 'مدفوع جزئياً',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الحجز')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                        'no-show' => 'لم يحضر',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('حالة الدفع')
                    ->options([
                        'unpaid' => 'غير مدفوع',
                        'paid' => 'مدفوع',
                        'refunded' => 'مسترجع',
                        'partial' => 'مدفوع جزئياً',
                    ]),
                Tables\Filters\SelectFilter::make('specialist_id')
                    ->label('المختص')
                    ->relationship('specialist', 'name'),
                Tables\Filters\SelectFilter::make('service_id')
                    ->label('الخدمة')
                    ->relationship('service', 'name'),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('تأكيد الحجز')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Booking $record) => $record->update(['status' => 'confirmed']))
                    ->requiresConfirmation()
                    ->hidden(fn (Booking $record) => $record->status !== 'pending')
                    ->successNotificationTitle('تم تأكيد الحجز بنجاح'),
                Tables\Actions\Action::make('complete')
                    ->label('إكمال الحجز')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->action(fn (Booking $record) => $record->update(['status' => 'completed']))
                    ->requiresConfirmation()
                    ->hidden(fn (Booking $record) => $record->status !== 'confirmed')
                    ->successNotificationTitle('تم إكمال الحجز بنجاح'),
                Tables\Actions\Action::make('cancel')
                    ->label('إلغاء الحجز')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Booking $record) => $record->update(['status' => 'cancelled']))
                    ->requiresConfirmation()
                    ->hidden(fn (Booking $record) => in_array($record->status, ['completed', 'cancelled', 'no-show']))
                    ->successNotificationTitle('تم إلغاء الحجز بنجاح'),
                Tables\Actions\Action::make('mark_as_paid')
                    ->label('تعليم كمدفوع')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->action(fn (Booking $record) => $record->update(['payment_status' => 'paid']))
                    ->requiresConfirmation()
                    ->hidden(fn (Booking $record) => $record->payment_status === 'paid')
                    ->successNotificationTitle('تم تحديث حالة الدفع بنجاح'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('confirm_selected')
                        ->label('تأكيد المحدد')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each(fn (Booking $booking) => $booking->status === 'pending' ? $booking->update(['status' => 'confirmed']) : null))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('cancel_selected')
                        ->label('إلغاء المحدد')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each(fn (Booking $booking) => !in_array($booking->status, ['completed', 'cancelled', 'no-show']) ? $booking->update(['status' => 'cancelled']) : null))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_as_paid_selected')
                        ->label('تعليم كمدفوع')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each(fn (Booking $booking) => $booking->payment_status !== 'paid' ? $booking->update(['payment_status' => 'paid']) : null))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
