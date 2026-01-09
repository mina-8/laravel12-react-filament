<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Models\Item;
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

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('quantity')
                //     ->label(__('package.quantity'))
                //     ->required()
                //     ->numeric()
                //     ->default(1)
                //     ->minValue(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('item.title')
                    ->label(__('package.item'))
                    ->searchable(),
                TextColumn::make('unit')
                    ->label(__('package.item'))
                    ->searchable(),
                TextColumn::make('pivot.quantity')
                    ->label(__('package.quantity'))
                    ->numeric(),
                TextColumn::make('price')
                    ->label(__('package.unit_price'))
                    ->money('EGP'),
                TextColumn::make('total_price')
                    ->label(__('package.total_price'))
                    ->state(function ($record) {
        return $record->price * $record->pivot->quantity;
    })
                    ->money('EGP'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()

                    ->form([
                        Select::make('item_id')
                            ->options(
                                Item::where('is_active', true)
                                    ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->reactive()
                            ->required(),
                        Select::make('item_unit_id')
                            ->label(__('package.item_unit'))
                            ->options(function (callable $get) {
                                $itemId = $get('item_id');
                                if (!$itemId) {
                                    return [];
                                }
                                $item = Item::find($itemId);
                                return $item->itemUnits()
                                    ->where('is_active', true)
                                    ->get()
                                    ->mapWithKeys(function ($unit) {
                                        return [$unit->id => $unit->size_unit . ' - ' . $unit->unit . ' - ' . number_format($unit->price, 2) . ' EGP'];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->required(),
                        TextInput::make('quantity')
                            ->label(__('package.quantity'))
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        $livewire->getRelationship()->attach(
                            $data['item_unit_id'],
                            ['quantity' => $data['quantity']]
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        TextInput::make('quantity')
                            ->label(__('package.quantity'))
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
