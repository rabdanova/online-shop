<?php
namespace Controllers;
use Model\Product;
use Model\UserProduct;
use Service\AuthService;

class ProductController extends BaseController
{
    private Product $productModel;
    private UserProduct $userProductModel;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
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

    public function addProduct()
    {
        if (!$this->authService->check()) {
            header('Location: ./login.php');
            exit;
        }

        $errors = $this->Validate($_POST);

        if (empty($errors)) {
            $amount = 1;
            $productId = $_POST["product_id"];
            $user = $this->authService->getCurrentUser();


            $result = $this->userProductModel->getOneByProductAndUserId($user->getId(), $productId);

            if (empty($result)) {
                $this->userProductModel->insertById($user->getId(), $productId, $amount);
            } else {
                $newAmount = $result->getAmount() + $amount;
                $this->userProductModel->updateById($user->getId(), $productId, $newAmount);
            }

            header("Location: catalog");

        }
    }

    public function decreaseProduct()
    {
        if (!$this->authService->check()) {
            header('Location: ./login.php');
            exit;
        }

        $errors = $this->Validate($_POST);

        if (empty($errors)) {
            $productId = $_POST["product_id"];
            $user = $this->authService->getCurrentUser();
            $amount = 1;

            $result = $this->userProductModel->getOneByProductAndUserId($user->getId(), $productId);

            if (!empty($result)) {
                $newAmount = $result->getAmount() - $amount;
                    if ($newAmount >= 1 )
                    {
                        $this->userProductModel->updateById($user->getId(), $productId, $newAmount);
                    } else
                    {
                        $this->userProductModel->deleteOneByProductAndUserId($productId, $user->getId());
                    }
            }

            header("Location: catalog");

        }
    }

    private function Validate(array $data): array
    {
        $errors = [];

        $errorProductId = $this->ValidateProductId($data);
        if (!empty($errorProductId)) {
            $errors['product_id'] = $errorProductId;
        }

        return $errors;
    }

    private function ValidateProductId($data): string|null
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

