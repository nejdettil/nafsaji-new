<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'إدارة المدفوعات';
    protected static ?string $navigationLabel = 'المدفوعات';
    protected static ?string $modelLabel = 'مدفوعات';
    protected static ?string $pluralModelLabel = 'المدفوعات';
    protected static ?int $navigationSort = 2;
    
    // Ocultar de la navegación ya que la tabla no existe
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تفاصيل الدفع')
                    ->schema([
                        Forms\Components\Select::make('booking_id')
                            ->label('الحجز')
                            ->relationship('booking', 'id', function ($query) {
                                return $query
                                    ->whereIn('status', ['confirmed', 'completed'])
                                    ->orderBy('created_at', 'desc');
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => "#{$record->id} - {$record->user->name} ({$record->date} {$record->time})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\Select::make('user_id')
                                    ->label('المستخدم')
                                    ->relationship('user', 'name')
                                    ->required(),
                                Forms\Components\Select::make('specialist_id')
                                    ->label('المختص')
                                    ->relationship('specialist', 'name')
                                    ->required(),
                                Forms\Components\DatePicker::make('date')
                                    ->label('التاريخ')
                                    ->required(),
                                Forms\Components\TimePicker::make('time')
                                    ->label('الوقت')
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->required()
                            ->suffix('ر.س'),
                        Forms\Components\Select::make('payment_method')
                            ->label('طريقة الدفع')
                            ->options([
                                'cash' => 'نقدي',
                                'bank_transfer' => 'تحويل بنكي',
                                'credit_card' => 'بطاقة ائتمانية',
                                'online' => 'دفع إلكتروني',
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('حالة الدفع')
                            ->options([
                                'pending' => 'قيد المعالجة',
                                'completed' => 'مكتمل',
                                'refunded' => 'مسترجع',
                                'failed' => 'فشل',
                            ])
                            ->required()
                            ->default('completed'),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('رقم العملية')
                            ->placeholder('رقم المرجع أو رقم المعاملة'),
                        Forms\Components\DateTimePicker::make('payment_date')
                            ->label('تاريخ الدفع')
                            ->default(now())
                            ->required(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('ملاحظات')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات')
                            ->placeholder('أي ملاحظات إضافية حول هذه العملية')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('receipt')
                            ->label('إيصال الدفع')
                            ->directory('payment-receipts')
                            ->image()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('رقم العملية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.id')
                    ->label('رقم الحجز')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.user.name')
                    ->label('العميل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('booking.specialist.name')
                    ->label('المختص')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('طريقة الدفع')
                    ->colors([
                        'success' => 'cash',
                        'info' => 'bank_transfer',
                        'warning' => 'credit_card',
                        'primary' => 'online',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'نقدي',
                        'bank_transfer' => 'تحويل بنكي',
                        'credit_card' => 'بطاقة ائتمانية',
                        'online' => 'دفع إلكتروني',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'completed',
                        'danger' => 'refunded',
                        'warning' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'refunded' => 'مسترجع',
                        'failed' => 'فشل',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('تاريخ الدفع')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
