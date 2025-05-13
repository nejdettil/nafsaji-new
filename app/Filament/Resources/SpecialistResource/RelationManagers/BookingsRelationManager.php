<?php

namespace App\Filament\Resources\SpecialistResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $recordTitleAttribute = 'id';
    
    protected static ?string $title = 'الحجوزات';
    
    public function form(Form $form): Form
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('رقم الحجز')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة حجز جديد'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('تأكيد')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['status' => 'confirmed']))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->status !== 'pending')
                    ->successNotificationTitle('تم تأكيد الحجز بنجاح'),
                Tables\Actions\Action::make('complete')
                    ->label('إكمال')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->action(fn ($record) => $record->update(['status' => 'completed']))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->status !== 'confirmed')
                    ->successNotificationTitle('تم إكمال الحجز بنجاح'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('confirm_selected')
                        ->label('تأكيد المحدد')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->status === 'pending' ? $record->update(['status' => 'confirmed']) : null))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
}
