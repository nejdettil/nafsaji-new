<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialistResource\Pages;
use App\Filament\Resources\SpecialistResource\RelationManagers;
use App\Models\Specialist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialistResource extends Resource
{
    protected static ?string $model = Specialist::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'إدارة المختصين';
    protected static ?string $navigationLabel = 'المختصين';
    protected static ?string $modelLabel = 'مختص';
    protected static ?string $pluralModelLabel = 'المختصين';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\FileUpload::make('avatar')
                            ->label('الصورة الشخصية')
                            ->image()
                            ->directory('specialists/avatars')
                            ->imagePreviewHeight('250')
                            ->maxSize(2048),
                    ])->columns(2),
                    
                Forms\Components\Section::make('التخصص و المعلومات المهنية')
                    ->schema([
                        Forms\Components\TextInput::make('speciality')
                            ->label('التخصص')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('bio')
                            ->label('السيرة الذاتية')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('مميز')
                            ->helperText('عرض المختص في قسم المميزين في الصفحة الرئيسية'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('تعطيل هذا الخيار سيخفي المختص من الموقع'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('الجدول والرسوم')
                    ->schema([
                        Forms\Components\Repeater::make('available_days')
                            ->label('الأيام المتاحة')
                            ->schema([
                                Forms\Components\Select::make('day')
                                    ->label('اليوم')
                                    ->options([
                                        'saturday' => 'السبت',
                                        'sunday' => 'الأحد',
                                        'monday' => 'الاثنين',
                                        'tuesday' => 'الثلاثاء',
                                        'wednesday' => 'الأربعاء',
                                        'thursday' => 'الخميس',
                                        'friday' => 'الجمعة',
                                    ])
                                    ->required(),
                                Forms\Components\TimePicker::make('start_time')
                                    ->label('من')
                                    ->seconds(false)
                                    ->required(),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('إلى')
                                    ->seconds(false)
                                    ->required(),
                            ])
                            ->columns(3),
                        Forms\Components\TextInput::make('price_per_hour')
                            ->label('السعر لكل ساعة (ريال)')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(fn (Specialist $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('speciality')
                    ->label('التخصص')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price_per_hour')
                    ->label('السعر/ساعة')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('عدد الحجوزات')
                    ->counts('bookings')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('الحالة')
                    ->options([
                        '1' => 'نشط',
                        '0' => 'غير نشط',
                    ]),
                Tables\Filters\SelectFilter::make('is_featured')
                    ->label('مميز')
                    ->options([
                        '1' => 'مميز',
                        '0' => 'غير مميز',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Specialist $record) => $record->is_active ? 'تعطيل' : 'تفعيل')
                    ->icon(fn (Specialist $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Specialist $record) => $record->is_active ? 'danger' : 'success')
                    ->action(fn (Specialist $record) => $record->update(['is_active' => !$record->is_active]))
                    ->requiresConfirmation()
                    ->successNotificationTitle('تم تغيير حالة المختص بنجاح'),
                Tables\Actions\Action::make('view_bookings')
                    ->label('عرض الحجوزات')
                    ->icon('heroicon-o-calendar')
                    ->url(fn (Specialist $record) => route('filament.admin.resources.bookings.index', ['tableFilters[specialist_id][value]' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialists::route('/'),
            'create' => Pages\CreateSpecialist::route('/create'),
            'edit' => Pages\EditSpecialist::route('/{record}/edit'),
        ];
    }
}
