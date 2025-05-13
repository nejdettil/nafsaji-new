<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    protected static ?string $navigationLabel = 'المستخدمين';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $pluralModelLabel = 'المستخدمين';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم الكامل')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('role')
                            ->label('الدور')
                            ->required()
                            ->options([
                                'admin' => 'مدير',
                                'specialist' => 'مختص',
                                'user' => 'مستخدم عادي',
                            ])
                            ->default('user'),
                        Forms\Components\FileUpload::make('avatar')
                            ->label('الصورة الشخصية')
                            ->image()
                            ->directory('users/avatars')
                            ->imagePreviewHeight('250')
                            ->maxSize(2048),
                    ])->columns(2),
                    
                Forms\Components\Section::make('كلمة المرور')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrateStateUsing(fn (?string $state): string => 
                                filled($state) ? bcrypt($state) : ''
                            )
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->columnSpanFull()
                            ->autocomplete('new-password'),
                    ])->collapsible(),
                    
                Forms\Components\Section::make('معلومات إضافية')
                    ->schema([
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('تاريخ تفعيل البريد')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('تعطيل هذا الخيار سيمنع المستخدم من تسجيل الدخول'),
                    ])->collapsible()->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('role')
                    ->label('الدور')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'specialist',
                        'success' => 'user',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'مدير',
                        'specialist' => 'مختص',
                        'user' => 'مستخدم',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('active')
                    ->label('نشط')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('مفعل')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->getStateUsing(fn (User $record): bool => $record->email_verified_at !== null),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('الحجوزات')
                    ->counts('bookings')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('الدور')
                    ->options([
                        'admin' => 'مدير',
                        'specialist' => 'مختص',
                        'user' => 'مستخدم عادي',
                    ]),
                Tables\Filters\Filter::make('email_verified')
                    ->label('مفعل البريد')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('email_not_verified')
                    ->label('غير مفعل البريد')
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
                Tables\Filters\Filter::make('active')
                    ->label('نشط')
                    ->query(fn (Builder $query): Builder => $query->where('active', true)),
                Tables\Filters\Filter::make('inactive')
                    ->label('غير نشط')
                    ->query(fn (Builder $query): Builder => $query->where('active', false)),
                Tables\Filters\Filter::make('created_at')
                    ->label('مسجل حديثاً')
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
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\Action::make('bookings')
                    ->label('الحجوزات')
                    ->icon('heroicon-o-calendar')
                    ->url(fn (User $record): string => route('filament.admin.resources.bookings.index', [
                        'tableFilters[user][value]' => $record->id,
                    ]))
                    ->visible(fn (User $record): bool => $record->role !== 'admin'),
                Tables\Actions\Action::make('verify_email')
                    ->label('تفعيل البريد')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->forceFill([
                            'email_verified_at' => now(),
                        ])->save();
                    })
                    ->visible(fn (User $record): bool => $record->email_verified_at === null),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (User $record): string => $record->active ? 'إلغاء تنشيط' : 'تنشيط')
                    ->icon(fn (User $record): string => $record->active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (User $record): string => $record->active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['active' => !$record->active]);
                    }),
                Tables\Actions\Action::make('send_notification')
                    ->label('إرسال إشعار')
                    ->icon('heroicon-o-bell-alert')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الإشعار')
                            ->required(),
                        Forms\Components\Textarea::make('content')
                            ->label('محتوى الإشعار')
                            ->required(),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->notify(new \App\Notifications\GeneralNotification(
                            $data['title'],
                            $data['content']
                        ));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                    Tables\Actions\BulkAction::make('verify_emails')
                        ->label('تفعيل البريد الإلكتروني')
                        ->icon('heroicon-o-envelope')
                        ->action(function (Collection $records) {
                            $records->each(function (User $record) {
                                if ($record->email_verified_at === null) {
                                    $record->forceFill([
                                        'email_verified_at' => now(),
                                    ])->save();
                                }
                            });
                        }),
                    Tables\Actions\BulkAction::make('activate_users')
                        ->label('تنشيط المستخدمين')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(function (User $record) {
                                $record->update(['active' => true]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('deactivate_users')
                        ->label('إلغاء تنشيط المستخدمين')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each(function (User $record) {
                                $record->update(['active' => false]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('send_notification')
                        ->label('إرسال إشعار جماعي')
                        ->icon('heroicon-o-bell-alert')
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->label('عنوان الإشعار')
                                ->required(),
                            Forms\Components\Textarea::make('content')
                                ->label('محتوى الإشعار')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function (User $record) use ($data) {
                                $record->notify(new \App\Notifications\GeneralNotification(
                                    $data['title'],
                                    $data['content']
                                ));
                            });
                        }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
