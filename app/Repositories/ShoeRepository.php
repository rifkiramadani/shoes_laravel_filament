<?php
namespace App\Repository;

use App\Models\Shoe;
use App\Repositories\Contracts\ShoeRepositoryInterface;

class ShoeRepository implements ShoeRepositoryInterface {

    public function getPopularShoes($limit = 4) {
        return Shoe::where('is_popular', true)->take($limit)->get();
    }

    public function getAllNewShoes() {
        return Shoe::latest()->get();
    }

    public function searchByName(string $keyword) {
        return Shoe::where('name', 'LIKE', '%' . $keyword . '%');
    }

    public function find($id) {
        return Shoe::find($id);
    }

    //ambil harga sepatu berdasarkan id sepatu yang ada di method find() di atas
    public function getPrice($shoeId) {
        $shoe = $this->find($shoeId);
        return $shoe ? $shoe->price : 0;
    }
}


?>
