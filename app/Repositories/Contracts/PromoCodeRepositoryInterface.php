<?php
namespace App\Repositories\Contracts;

interface PromoCodeRepositoryInterface {

    //ambil semua promo code
    public function getAllPromoCode();

    //ambil promocode berdasarkan promocode yang di inputkan oleh user
    public function findByCode(string $code);
}

?>
