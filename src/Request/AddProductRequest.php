<?php

namespace Request;
use Model\Product;
class AddProductRequest
{
    private Product $productModel;

    public function __construct(private array $data)
    {
        $this->productModel = new Product();
    }
    public function getProductId() : int
    {
        return $this->data['product_id'];
    }

    public function validate(): array
    {
        $errors = [];

        $errorProductId = $this->validateProductId();
        if (!empty($errorProductId)) {
            $errors['product_id'] = $errorProductId;
        }

        return $errors;
    }

    private function validateProductId(): string|null
    {
        if (isset($this->data['product_id'])) {
            $productId = $this->data['product_id'];

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