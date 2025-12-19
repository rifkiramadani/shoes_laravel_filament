<?php

namespace App\Filament\Resources\Shoes\Pages;

use App\Filament\Resources\Shoes\ShoeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditShoe extends EditRecord
{
    protected static string $resource = ShoeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
