<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('filament-panels::resources/pages/admin.title');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::resources/pages/admin.title');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament-panels::resources/pages/admin.title');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament-panels::resources/pages/admin.fields.name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('filament-panels::resources/pages/admin.fields.email'))
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'admins', column: 'email', ignoreRecord: true),
                TextInput::make('password')
                    ->label(__('filament-panels::resources/pages/admin.fields.password'))
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->visibleOn('create'),
                Select::make('roles')
                    ->label(__('filament-panels::resources/pages/admin.fields.role'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn($record) => __('filament-panels::resources/pages/admin.roles.' . $record->name)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-panels::resources/pages/admin.fields.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('filament-panels::resources/pages/admin.fields.email'))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('filament-panels::resources/pages/admin.fields.role'))
                    ->formatStateUsing(fn ($state) =>  __('filament-panels::resources/pages/admin.roles.' . $state) )
                    ->listWithLineBreaks()
                    ->searchable(query: function ($query, $search) {
                        $translatedRoles = [
                            'super_admin' => __('filament-panels::resources/pages/admin.roles.super_admin'),
                            'admin' => __('filament-panels::resources/pages/admin.roles.admin'),
                            'crm' => __('filament-panels::resources/pages/admin.roles.crm'),
                        ];
                        $roleKeys = array_keys(array_filter($translatedRoles, function ($translation) use ($search) {
                            return str_contains(strtolower($translation), strtolower($search));
                        }));
                        if (!empty($roleKeys)) {
                            $query->whereHas('roles', function ($q) use ($roleKeys) {
                                $q->whereIn('name', $roleKeys);
                            });
                        }
                    }),
                TextColumn::make('created_at')
                    ->label(__('filament-panels::resources/pages/admin.fields.created_at'))
                    ->dateTime()
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
