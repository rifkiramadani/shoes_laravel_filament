<?php

namespace App\Filament\Resources\ProductTransactions\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use App\Models\ProductTransaction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class ProductTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('shoe.thumbnail')
                    ->disk('public'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('booking_trx_id')
                    ->searchable(),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
                    ->label('Terverifikasi')
            ])
            ->filters([
                // TrashedFilter::make(),
                SelectFilter::make('shoe_id')
                    ->label('Shoe')
                    ->relationship('shoe', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->action(function (ProductTransaction $record) {
                        $record->is_paid = true;
                        $record->save();

                        //triger notifikasi custom
                        Notification::make()
                            ->title('Order Approved')
                            ->body('The Order has been sucessfully approved')
                            ->success()
                            ->send();
                    })
                    ->color('success')
                    ->requiresConfirmation() //memunculkan alert
                    ->visible(fn(ProductTransaction $record) => !$record->is_paid),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
