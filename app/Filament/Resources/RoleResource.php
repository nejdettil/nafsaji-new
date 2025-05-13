<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'إدارة النظام';
    protected static ?string $navigationLabel = 'الأدوار والصلاحيات';
    protected static ?string $modelLabel = 'دور';
    protected static ?string $pluralModelLabel = 'الأدوار';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تفاصيل الدور')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الدور')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('guard_name')
                            ->label('اسم الحارس')
                            ->default('web')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('وصف الدور')
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('الصلاحيات')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('الصلاحيات')
                            ->relationship('permissions', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Permission $record) => match ($record->name) {
                                'admin.access' => 'دخول لوحة الإدارة',
                                'users.view' => 'عرض المستخدمين',
                                'users.create' => 'إضافة مستخدمين',
                                'users.edit' => 'تعديل المستخدمين',
                                'users.delete' => 'حذف المستخدمين',
                                'specialists.view' => 'عرض المختصين',
                                'specialists.create' => 'إضافة مختصين',
                                'specialists.edit' => 'تعديل المختصين',
                                'specialists.delete' => 'حذف المختصين',
                                'services.view' => 'عرض الخدمات',
                                'services.create' => 'إضافة خدمات',
                                'services.edit' => 'تعديل الخدمات',
                                'services.delete' => 'حذف الخدمات',
                                'bookings.view' => 'عرض الحجوزات',
                                'bookings.create' => 'إضافة حجوزات',
                                'bookings.edit' => 'تعديل الحجوزات',
                                'bookings.delete' => 'حذف الحجوزات',
                                'notifications.view' => 'عرض الإشعارات',
                                'notifications.create' => 'إرسال إشعارات',
                                'settings.view' => 'عرض الإعدادات',
                                'settings.edit' => 'تعديل الإعدادات',
                                default => $record->name,
                            })
                            ->bulkToggleable()
                            ->columns(3)
                            ->searchable()
                            ->gridDirection('rtl'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدور')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->label('اسم الحارس')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('عدد الصلاحيات')
                    ->counts('permissions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('عدد المستخدمين')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
