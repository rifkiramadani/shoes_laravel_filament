<?php
namespace App\Services;


use App\Repositories\Contracts\ShoeRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

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
        $categories = $this->categoryRepository->getAllCategories();
        $popularShoes = $this->shoeRepository->getPopularShoes(4);
        $newShoes = $this->shoeRepository->getAllNewShoes();

        return compact('categories', 'popularShoes', 'newShoes');
    }
}


?>
