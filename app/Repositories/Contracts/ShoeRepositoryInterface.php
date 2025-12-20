<?php
namespace App\Repositories\Contracts;

interface ShoeRepositoryInterface {
    //mengambil sepatu yang populer dengan batasan limit tertentu
    public function getPopularShoes($limit);

    //mengambil semua sepatu
    public function getAllNewShoes();

    //mengambil sepatu berdasarkan id
    public function find($id);

    //mengambil harga berdasarkan ticket id atau id sepatu yang ada di method
    public function getPrice($ticketId);
}


?>
