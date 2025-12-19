<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            FileUpload::make('icon')
                ->image()
                ->disk('public')
                ->directory('categories')
                ->required()
            ]);
    }
}
