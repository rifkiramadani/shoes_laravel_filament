<?php

namespace App\Filament\Resources\ProductTransactions\Schemas;

use App\Models\Shoe;
use App\Models\PromoCode;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextArea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Wizard\Step;

class ProductTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Product and Price')
                        ->schema([
                                 Select::make('shoe_id')
                                ->relationship('shoe', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                //state yang belum ada isi
                                ->afterStateUpdated(function($state, callable $get, callable $set) {

                                //mengambil data sepatu id, harga, quantity dari form bawah, dan subTotalAmount
                                $shoe = Shoe::find($state);
                                $price = $shoe ? $shoe->price : 0;
                                $quantity = $get('quantity');
                                $subTotalAmount = $price * $quantity;

                                //mengambil price dan sub total amount dari form paling bawah
                                $set('price', $price);
                                $set('sub_total_amount', $subTotalAmount);

                                //mendapatkan diskon dan men-set grand total amount
                                $discount = $get('discount_amount') ?? 0;
                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set('grand_total_amount', $grandTotalAmount);

                                //ambil sizes dan id dari sepatu dan jadikan ke array, jika tidak ada kembalikan array kosong
                                $sizes = $shoe ? $shoe->shoeSizes->pluck('size', 'id')->toArray() : [];
                                $set('shoe_sizes', $sizes);

                            })
                            //state yang ada isinya atau edit data
                            ->afterStateHydrated(function (callable $get, callable $set, $state) {
                                $shoeId = $state;
                                if($shoeId) {
                                    $shoe = Shoe::find($shoeId);
                                    $sizes = $shoe ? $shoe->shoeSizes->pluck('size', 'id')->toArray() : [];
                                    $set('shoe_sizes', $sizes);
                                }
                            }),
                            Select::make('shoe_size')
                                ->label('Shoe Size')
                                ->options(function(callable $get) {
                                    $sizes = $get('shoe_sizes');
                                    return is_array($sizes) ? $sizes : [];
                                })
                                ->required()
                                ->live(),
                            TextInput::make('quantity')
                                ->required()
                                ->numeric()
                                ->prefix('Qty')
                                ->live()
                                ->afterStateUpdated(function($state, callable $get, callable $set) {
                                    //untuk menset harga
                                    $price = $get('price');
                                    $quantity = $state;
                                    $subTotalAmount = $price * $quantity;

                                    $set('sub_total_amount', $subTotalAmount);

                                    //set diskon
                                    $discount = $get('discount_amount') ?? 0;
                                    $grandTotalAmount = $subTotalAmount - $discount;
                                    $set('grand_total_amount', $grandTotalAmount);
                                }),

                            Select::make('promo_code_id')
                                ->relationship('promoCode', 'code')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function($state, callable $get, callable $set) {
                                    //ambil sub total amount terakhir
                                    $subTotalAmount = $get('sub_total_amount');
                                    //ambil promocode langsung dari model
                                    $promoCode = PromoCode::find($state);
                                    $discount = $promoCode ? $promoCode->discount_amount : 0;

                                    //set discount amount sekarang dengan yang baru
                                    $set('discount_amount', $discount);

                                    $grandTotalAmount = $subTotalAmount - $discount;
                                    $set('grand_total_amount', $grandTotalAmount);
                                }),
                                TextInput::make('sub_total_amount')
                                    ->required()
                                    ->readOnly()
                                    ->numeric()
                                    ->prefix('IDR'),

                                TextInput::make('grand_total_amount')
                                    ->required()
                                    ->readOnly()
                                    ->numeric()
                                    ->prefix('IDR'),

                                TextInput::make('discount_amount')
                                    ->required()
                                    ->numeric()
                                    ->prefix('IDR'),
                            ]),

                        Step::make('Customer Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->required()
                                    ->maxLength(255),
                                TextArea::make('address')
                                    ->rows(5)
                                    ->required(),
                                TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('post_code')
                                    ->required()
                                    ->maxLength(255),
                        ]),

                        Step::make('Payment Information')
                            ->schema([
                                TextInput::make('booking_trx_id')
                                    ->required()
                                    ->maxLength(255),
                                ToggleButtons::make('is_paid')
                                    ->label('Apakah Sudah Membayar?')
                                    ->boolean()
                                    ->grouped()
                                    ->icons([
                                        true => Heroicon::OutlinedPencil,
                                        false => Heroicon::OutlinedClock
                                    ])
                                    ->required(),
                                FileUpload::make('proof')
                                    ->image()
                                    ->required()
                                    ->disk('public')
                                    ->directory('payment-proofs')
                            ]),
                        ])
                        ->columnSpan('full')
                        ->columns()
                        ->skippable()
                    ]);
                }
            }
