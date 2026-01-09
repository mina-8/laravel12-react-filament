<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Enums\UnitsEnum;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'itemUnits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('size_unit')
                    ->label(__('item.size_unit'))
                    ->required()
                    ->numeric(),
                Select::make('unit')
                    ->label(__('item.unit'))
                    ->options(UnitsEnum::options())
                    ->required(),
                TextInput::make('price')
                    ->label(__('item.price'))
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('size_unit')->label(__('item.size_unit'))->numeric(),
                TextColumn::make('unit')->label(__('item.unit'))
                ->sortable(),
                TextColumn::make('price')->label(__('item.price'))->money('egp', true),
            ])

            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
