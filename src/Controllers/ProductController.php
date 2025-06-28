<?php
namespace Controllers;
use Model\Product;
use Model\Review;
use Model\UserProduct;
use Request\AddProductRequest;
use Request\AddReviewRequest;
use Service\AuthService;

class ProductController extends BaseController
{
    private Product $productModel;
    private Review $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->reviewModel = new Review();
    }
    public function catalog()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit;
        }

        $products = $this->productModel->getAllProducts();
        require_once '../Views/catalog.php';
    }


    public function getProductPage(AddReviewRequest $request)
    {
        $productId = $request->getProductId();
        $product = $this->productModel->getByProductId($productId);

        $reviews = $this->reviewModel->getAllByProductId($productId);

        $averageRating = 0;
        if (!empty($reviews)) {
            $sum = 0;
            $count = count($reviews);
            foreach ($reviews as $review) {
                $sum += $review->getRating();
            }
            $averageRating = round($sum / $count, 2);
        }
        require_once '../Views/Product-page.php';
    }

    public function addReview(AddReviewRequest $request)
    {
        $userId = $this->authService->getCurrentUser()->getId();

        $this->reviewModel->create($request->getComment(),$request->getProductId(),$userId, $request->getRating());
        header("Location: catalog");

    }
}

