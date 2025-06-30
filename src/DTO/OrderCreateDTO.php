<?php

namespace DTO;

use Model\User;

class OrderCreateDTO
{
    public function __construct(
        private string $name,
        private string $phone_number,
        private string $comment,
        private string $address,
    ) {
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

}