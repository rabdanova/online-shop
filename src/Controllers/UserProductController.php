<?php

namespace Controllers;
use Model\UserProduct;
use Model\Product;
use Service\AuthService;

class UserProductController extends BaseController
{
    private UserProduct $userProductModel;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
    }
    public function cart()
    {
        session_start();

        if (!$this->authService->check()) {
            header("Location: /login");
            exit;
        }
        $user = $this->authService->getCurrentUser();

        $data = $this->userProductModel->getByUserId($user->getId());

        $cart = [];

        foreach ($data as $product) {

            $productId = $product->getProductId();
            $result = $this->productModel->getByProductId($productId);

            $result->setAmount($product->getAmount());
            $cart[] = $result;
        }

        require_once '../Views/userProducts.php';
    }
}