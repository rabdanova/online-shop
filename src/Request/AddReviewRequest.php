<?php

namespace Request;

class AddReviewRequest
{
    public function __construct(private array $data)
    {
    }

    public function getProductId() : int
    {
        return $this->data['product_id'];
    }

    public function getComment() : string
    {
        return $this->data['comment'];
    }

    public function getRating() : int
    {
        return $this->data['rating'];
    }


}