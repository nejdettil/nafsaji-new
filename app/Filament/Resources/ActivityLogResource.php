<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'سجل الأنشطة';
    
    protected static ?string $modelLabel = 'نشاط';
    
    protected static ?string $pluralModelLabel = 'سجل الأنشطة';
    
    protected static ?string $navigationGroup = 'النظام';
    
    protected static ?int $navigationSort = 99;

    public static function getNavigationBadge(): ?string
    {
        // التحقق من وجود الجدول قبل العد لتجنب الخطأ
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('activity_logs')) {
                return static::getModel()::count();
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('action')
                            ->label('الإجراء')
                            ->maxLength(255)
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('action_type')
                            ->label('نوع الإجراء')
                            ->maxLength(255)
                            ->disabled(),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('ip_address')
                            ->label('عنوان IP')
                            ->maxLength(45)
                            ->disabled(),
                        
                        Forms\Components\Textarea::make('user_agent')
                            ->label('متصفح المستخدم')
                            ->disabled(),
                        
                        Forms\Components\KeyValue::make('properties')
                            ->label('البيانات')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('الرقم')
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('action')
                    ->label('الإجراء')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('action_type')
                    ->label('نوع الإجراء')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        'restore' => 'info',
                        'login' => 'primary',
                        'logout' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'create' => 'إنشاء',
                        'update' => 'تحديث',
                        'delete' => 'حذف',
                        'restore' => 'استعادة',
                        'login' => 'تسجيل دخول',
                        'logout' => 'تسجيل خروج',
                        'view' => 'عرض',
                        'export' => 'تصدير',
                        'import' => 'استيراد',
                        'download' => 'تنزيل',
                        'upload' => 'رفع',
                        'send' => 'إرسال',
                        'other' => 'أخرى',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('subject_type')
                    ->label('نوع الكائن')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->sortable(),
                
                TextColumn::make('subject_id')
                    ->label('معرف الكائن')
                    ->sortable(),
                
                TextColumn::make('ip_address')
                    ->label('عنوان IP')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y - H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action_type')
                    ->label('نوع الإجراء')
                    ->options([
                        'create' => 'إنشاء',
                        'update' => 'تحديث',
                        'delete' => 'حذف',
                        'restore' => 'استعادة',
                        'login' => 'تسجيل دخول',
                        'logout' => 'تسجيل خروج',
                        'view' => 'عرض',
                        'export' => 'تصدير',
                        'import' => 'استيراد',
                        'download' => 'تنزيل',
                        'upload' => 'رفع',
                        'send' => 'إرسال',
                        'other' => 'أخرى',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
