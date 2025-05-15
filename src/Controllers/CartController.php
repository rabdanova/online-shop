<?php

class CartController
{
    public function cart()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        $userId = $_SESSION['user_id'];
        require_once "../Model/Cart.php";
        $cartModel = new Cart();
        $data = $cartModel->getByUserId($userId);

        $cart = [];

        foreach ($data as $product) {

            $productId = $product['product_id'];
            $result = $cartModel->getByProductId($productId);

            $result['amount'] = $product['amount'];
            $cart[] = $result;
        }

        require_once '../Views/cart.php';
    }
}