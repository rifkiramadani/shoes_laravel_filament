<?php
namespace App\Repositories;

use App\Models\PromoCode;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;

class PromoCodeRepository implements PromoCodeRepositoryInterface {

    //ambil semua promocode
    public function getAllPromoCode() {
        return PromoCode::latest()->get();
    }

    //ambil promocode berdasarkan promocode yang di inputkan oleh user
    public function findByCode($code) {
        return PromoCode::where('code', $code)->first();
    }
}


?>
