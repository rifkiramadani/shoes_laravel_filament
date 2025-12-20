<?php

namespace App\Filament\Resources\PromoCodes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class PromoCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
            ]);
    }
}
