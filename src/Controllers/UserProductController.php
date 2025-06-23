<?php

namespace Controllers;
use DTO\CartDTO;
use Model\UserProduct;
use Model\Product;
use Service\AuthService;
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

    public function addProduct()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser()->getId();
            $data = $_POST;
            $errors = $this->validate($data);

            if (empty($errors)) {
                $dto = new CartDTO($user, $data['product_id']);
                $this->cartService->addProduct($dto);
                header("Location: catalog");
            }
        } else {
            header('Location: ./login.php');
            exit;
        }
    }

    public function decreaseProduct()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $data = $_POST;
            $errors = $this->validate($data);

            if (empty($errors)) {
                $dto = new CartDTO($user, $data['product_id']);
                $this->cartService->decreaseProduct($dto);
                header("Location: catalog");
            }
        } else {
            header('Location: ./login.php');
            exit;
        }
    }

    private function validate(array $data): array
    {
        $errors = [];

        $errorProductId = $this->validateProductId($data);
        if (!empty($errorProductId)) {
            $errors['product_id'] = $errorProductId;
        }

        return $errors;
    }

    private function validateProductId($data): string|null
    {
        if (isset($data['product_id'])) {
            $productId = $data['product_id'];

            if (is_numeric($productId)) {

                $result = $this->productModel->getById($productId);

                if ($result === false) {
                    return "Продукта с таким id не существует";
                } else {
                    return NUll;
                }
            } else {
                return "Неправильный формат id";
            }
        } else {
            return 'Введите id продукта';
        }
    }
}