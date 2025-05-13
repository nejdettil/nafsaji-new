<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'إدارة الخدمات';
    protected static ?string $navigationLabel = 'الخدمات';
    protected static ?string $modelLabel = 'خدمة';
    protected static ?string $pluralModelLabel = 'الخدمات';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الخدمة')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الخدمة')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label('اسم الخدمة بالإنجليزية')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label('السعر')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->label('مدة الجلسة (بالدقائق)')
                            ->numeric()
                            ->default(60)
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('صورة الخدمة')
                            ->image()
                            ->directory('services/images')
                            ->imagePreviewHeight('250')
                            ->maxSize(2048),
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('تعطيل هذا الخيار سيخفي الخدمة من الموقع'),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('مميز')
                            ->helperText('عرض الخدمة في قسم الخدمات المميزة في الصفحة الرئيسية'),
                        Forms\Components\Textarea::make('short_description')
                            ->label('وصف مختصر')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('short_description_en')
                            ->label('وصف مختصر بالإنجليزية')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('تفاصيل الخدمة')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('الوصف التفصيلي')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('services/attachments'),
                        Forms\Components\RichEditor::make('description_en')
                            ->label('الوصف التفصيلي بالإنجليزية')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('services/attachments'),
                    ])->collapsible(),
                    
                Forms\Components\Section::make('إعدادات متقدمة')
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->label('الرابط المخصص')
                            ->helperText('سيتم إنشاؤه تلقائيًا من الاسم إذا تركته فارغاً')
                            ->maxLength(255),
                        // تم إزالة حقل ترتيب العرض لأنه غير موجود في قاعدة البيانات
                    ])->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->square()
                    ->defaultImageUrl(fn (Service $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الخدمة')
                    ->description(fn (Service $record): string => $record->name_en)
                    ->searchable(['name', 'name_en'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('المدة')
                    ->formatStateUsing(fn (int $state): string => "{$state} دقيقة")
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('الحجوزات')
                    ->counts('bookings')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('d/m/Y H:i')
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
                Tables\Filters\Filter::make('price_range')
                    ->label('نطاق السعر')
                    ->form([
                        Forms\Components\TextInput::make('price_from')
                            ->label('من')
                            ->numeric(),
                        Forms\Components\TextInput::make('price_until')
                            ->label('إلى')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['price_from'],
                                fn (Builder $query, $price): Builder => $query->where('price', '>=', $price),
                            )
                            ->when(
                                $data['price_until'],
                                fn (Builder $query, $price): Builder => $query->where('price', '<=', $price),
                            );
                    }),
                Tables\Filters\Filter::make('has_bookings')
                    ->label('لديها حجوزات')
                    ->query(fn (Builder $query): Builder => $query->has('bookings')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\Action::make('toggle_featured')
                    ->label(fn (Service $record): string => $record->is_featured ? 'إلغاء التمييز' : 'تمييز')
                    ->icon('heroicon-o-star')
                    ->color(fn (Service $record): string => $record->is_featured ? 'gray' : 'warning')
                    ->action(function (Service $record): void {
                        $record->update(['is_featured' => !$record->is_featured]);
                    }),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn (Service $record): string => $record->is_active ? 'إيقاف' : 'تنشيط')
                    ->icon(fn (Service $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Service $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(function (Service $record): void {
                        $record->update(['is_active' => !$record->is_active]);
                    }),
                Tables\Actions\Action::make('view_bookings')
                    ->label('الحجوزات')
                    ->icon('heroicon-o-calendar')
                    ->url(fn (Service $record): string => route('filament.admin.resources.bookings.index', [
                        'tableFilters[service][value]' => $record->id,
                    ])),
                Tables\Actions\Action::make('clone')
                    ->label('نسخ')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (Service $record): void {
                        $clone = $record->replicate();
                        $clone->name = $record->name . ' (نسخة)';
                        $clone->name_en = $record->name_en . ' (Copy)';
                        $clone->slug = $record->slug . '-copy';
                        $clone->is_featured = false;
                        $clone->save();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تنشيط الخدمات')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Builder $query): void {
                            $query->update(['is_active' => true]);
                        }),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('إيقاف الخدمات')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Builder $query): void {
                            $query->update(['is_active' => false]);
                        }),
                    Tables\Actions\BulkAction::make('feature')
                        ->label('تمييز الخدمات')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Builder $query): void {
                            $query->update(['is_featured' => true]);
                        }),
                    Tables\Actions\BulkAction::make('unfeature')
                        ->label('إلغاء تمييز الخدمات')
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Builder $query): void {
                            $query->update(['is_featured' => false]);
                        }),
                ])
                ->label('إجراءات جماعية'),
            ])
            ->defaultSort('id', 'asc');
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
