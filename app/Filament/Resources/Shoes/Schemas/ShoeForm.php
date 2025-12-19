<?php

namespace App\Filament\Resources\Shoes\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Select;

class ShoeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('General')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        FileUpload::make('thumbnail')
                            ->image()
                            ->disk('public')
                            ->directory('thumbnails')
                            ->required(),
                        Repeater::make('photos')
                            ->relationship('shoePhotos')
                            ->schema([
                                FileUpload::make('photo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('shoe-photos')
                                    ->required(),
                            ]),
                        Repeater::make('sizes')
                            ->relationship('shoeSizes')
                            ->schema([
                                TextInput::make('size')
                                    ->required(),
                            ])
                            ]),
                Fieldset::make('Addtional')
                    ->columnSpan(2)
                    ->schema([
                        TextArea::make('about')
                            ->required(),
                        Select::make('is_popular')
                            ->required()
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular',
                            ]),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('stock')
                            ->numeric()
                            ->required()
                            ->prefix('Qty')
                    ])
            ]);
    }
}
