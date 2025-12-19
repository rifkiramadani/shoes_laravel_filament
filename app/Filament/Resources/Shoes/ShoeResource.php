<?php

namespace App\Filament\Resources\Shoes;

use App\Filament\Resources\Shoes\Pages\CreateShoe;
use App\Filament\Resources\Shoes\Pages\EditShoe;
use App\Filament\Resources\Shoes\Pages\ListShoes;
use App\Filament\Resources\Shoes\Schemas\ShoeForm;
use App\Filament\Resources\Shoes\Tables\ShoesTable;
use App\Models\Shoe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShoeResource extends Resource
{
    protected static ?string $model = Shoe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ShoeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShoesTable::configure($table);
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
            'index' => ListShoes::route('/'),
            'create' => CreateShoe::route('/create'),
            'edit' => EditShoe::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
