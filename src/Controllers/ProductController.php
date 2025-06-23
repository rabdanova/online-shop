<?php
namespace Controllers;
use Model\Product;
use Model\Review;
use Model\UserProduct;
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


    public function getProductPage()
    {
        $productId = $_POST["product_id"];
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

    public function addReview()
    {
        $userId = $this->authService->getCurrentUser()->getId();

        $this->reviewModel->create($_POST["comment"],$_POST["product_id"] ,$userId, $_POST["rating"] );
        header("Location: catalog");

    }



}

