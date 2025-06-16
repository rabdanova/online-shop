<?php

namespace Controllers;
use Model\UserProduct;
use Model\Product;
class UserProductController
{
    public function cart()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $userId = $_SESSION['user_id'];

        $userProductModel = new UserProduct();
        $productModel = new Product();
        $data = $userProductModel->getByUserId($userId);

        $cart = [];

        foreach ($data as $product) {

            $productId = $product->getProductId();
            $result = $productModel->getByProductId($productId);

            $result->setAmount($product->getAmount());
            $cart[] = $result;
        }

        require_once '../Views/userProducts.php';
    }
}