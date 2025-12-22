<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\FrontService;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService) {
        $this->frontService = $frontService;
    }

    public function index() {
        $data = $this->frontService->getFrontPageData();
        return view('front.index', [
            'categories' => $data['categories'],
            'popularShoes' => $data['popularShoes'],
            'newShoes' => $data['newShoes'],
        ]);
    }

    public function details(Shoe $shoe) {
        return view('front.details', compact('shoe'));
    }

    public function category(Category $category) {
        return view('front.category', compact('category'));
    }

    public function search(Request $request) {
        $keyword = $request->input('keyword');

        $shoes = $this->frontService->searchShoes($keyword);

        return view('front.search', [
            'shoes' => $shoes,
            'keyword' => $keyword
        ]);
    }
}
