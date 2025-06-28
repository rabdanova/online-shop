<?php

namespace Controllers;
use DTO\CartDTO;
use Model\UserProduct;
use Model\Product;
use Request\AddProductRequest;
use Request\DecreaseProductRequest;
use Service\CartService;

class UserProductController extends BaseController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->cartService = new CartService();
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

    public function addProduct(AddProductRequest $request)
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $errors = $request->validate();

            if (empty($errors)) {
                $dto = new CartDTO($user, $request->getProductId());
                $this->cartService->addProduct($dto);
                header("Location: catalog");
            }
        } else {
            header('Location: ./login.php');
            exit;
        }
    }

    public function decreaseProduct(DecreaseProductRequest $request)
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $errors = $request->validate();

            if (empty($errors)) {
                $dto = new CartDTO($user, $request->getProductId());
                $this->cartService->decreaseProduct($dto);
                header("Location: catalog");
            }
        } else {
            header('Location: ./login.php');
            exit;
        }
    }


}