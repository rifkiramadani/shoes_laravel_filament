<?php

namespace App\Models;

use App\Models\Shoe;
use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'booking_trx_id',
        'city',
        'post_code',
        'proof',
        'shoe_size',
        'address',
        'quantity',
        'sub_total_amount',
        'grand_total_amount',
        'discount_amount',
        'is_paid',
        'shoe_id',
        'promo_code_id'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public static function generateUniqueTrxId()
    {
        $prefix = 'SS';

        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    public function shoe()
    {
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }

    public function shoeSize() {
        return $this->belongsTo(ShoeSize::class, 'shoe_size');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

}
