<?php
namespace App\Repositories;

use App\Repository\Contracts\PromoCodeRepositoryInterface;

class PromoCodeRepository implements PromoCodeRepositoryInterface {

    //ambil semua promocode
    public function getAllPromoCode() {
        return PromoCode::latest()->get();
    }

    //ambil promocode berdasarkan promocode yang di inputkan oleh user
    public function findByCode($code) {
        return PromoCode::where('code', $code);
    }
}


?>
