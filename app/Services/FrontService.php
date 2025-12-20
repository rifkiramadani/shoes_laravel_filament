<?php
namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterfaces;
use App\Repositories\Contracts\ShoeRepositoryInterfaces;

class FrontService {
    protected $categoryRepository;
    protected $shoeRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ShoeRepositoryInterface $shoeRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->shoeRepository = $shoeRepository;
    }

    public function searchShoes(string $keyword) {
        return $this->shoeRepository->searchByName($keyword);
    }

    public function getFrontPageData() {
        $categories = $this->categoryRepository->getAllCategory();
        $popularShoes = $this->shoeRepository->getPopularShoes(4);
        $newShoes = $this->shoeRepository->getAllNewShoes();

        return compact('categories', 'popularShoes', 'newShoes');
    }
}


?>
