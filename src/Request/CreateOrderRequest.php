<?php

namespace Request;

class CreateOrderRequest
{
    public function __construct(private array $data)
    {

    }
    public function getName()
    {
        return $this->data['name'];
    }
    public function getPhoneNumber()
    {
        return $this->data['phone_number'];
    }
    public function getComment()
    {
        return $this->data['comment'];
    }
    public function getAddress()
    {
        return $this->data['address'];
    }
    public function validateOrder(): array
    {
        $errors = [];

        $errorName = $this->validateName();
        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }

        $errorPhoneNumber = $this->validatePhoneNumber();
        if (!empty($errorPhoneNumber)) {
            $errors['phone_number'] = $errorPhoneNumber;
        }

        $errorAddress = $this->validateAddress();
        if (!empty($errorAddress)) {
            $errors['address'] = $errorAddress;
        }

        return $errors;
    }

    public function validateName(): null|string
    {
        if (isset($this->data['name'])) {
            $name = $this->data['name'];
            if ((strlen($name) <= 3)) {
                return 'Недопустимая длина имени';
            } else {
                return NULL;
            }

        } else {
            return 'Введите имя пользователя';
        }
    }

    public function validatePhoneNumber(): null|string
    {
        if (isset($this->data['phone_number'])) {
            $phone = $this->data['phone_number'];

            if (!is_numeric($phone)) {
                return 'Введите число';
            } else {
                if ($phone > 0) {
                    return null;
                } else {
                    return 'Введите положительное число';
                }
            }
        } else {
            return 'Введите номер телефона';
        }
    }

    public function validateAddress(): null|string
    {
        if (isset($this->data['address'])) {
            $address = $this->data['address'];

            if (is_numeric($address)) {
                return 'Некорректный формат названия города';
            } else {
                return NULL;
            }
        }

        return 'Введите название населенного пункта';
    }
}