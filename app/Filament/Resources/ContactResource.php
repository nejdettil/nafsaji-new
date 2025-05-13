<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    protected static ?string $navigationLabel = 'رسائل الاتصال';
    protected static ?string $modelLabel = 'رسالة اتصال';
    protected static ?string $pluralModelLabel = 'رسائل الاتصال';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تفاصيل الرسالة')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1)
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1)
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpan(1)
                            ->disabled(),
                        Forms\Components\TextInput::make('subject')
                            ->label('الموضوع')
                            ->maxLength(255)
                            ->columnSpan(1)
                            ->disabled(),
                        Forms\Components\Textarea::make('message')
                            ->label('الرسالة')
                            ->required()
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('الإجراءات')
                    ->schema([
                        Forms\Components\Toggle::make('is_read')
                            ->label('تم القراءة')
                            ->default(false)
                            ->helperText('تحديد هذا الخيار يعني أنك قرأت هذه الرسالة'),
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات إدارية')
                            ->helperText('ملاحظات خاصة بالإدارة حول هذه الرسالة')
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('الموضوع')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('message')
                    ->label('الرسالة')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_read')
                    ->label('تمت القراءة')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإرسال')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_read')
                    ->label('الحالة')
                    ->options([
                        '0' => 'غير مقروءة',
                        '1' => 'مقروءة',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle('تم تحديث حالة الرسالة بنجاح'),
                Tables\Actions\Action::make('mark_as_read')
                    ->label('تمت القراءة')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (Contact $record) => $record->update(['is_read' => true]))
                    ->hidden(fn (Contact $record) => $record->is_read)
                    ->successNotificationTitle('تم تحديث حالة الرسالة بنجاح'),
                Tables\Actions\Action::make('reply')
                    ->label('الرد')
                    ->icon('heroicon-o-paper-airplane')
                    ->url(fn (Contact $record) => "mailto:{$record->email}?subject=رد على استفسارك: {$record->subject}")
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('تحديد كمقروءة')
                        ->icon('heroicon-o-check')
                        ->action(fn (Builder $query) => $query->update(['is_read' => true]))
                        ->successNotificationTitle('تم تحديث حالة الرسائل بنجاح'),
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_read', false)->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('is_read', false)->count() > 0 ? 'warning' : null;
    }
}
