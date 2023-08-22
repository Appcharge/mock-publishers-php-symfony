<?php

namespace App\Controller\Player\Models;

class Product
{
    public int $amount;
    public string $sku;
    public string $name;

    public function __construct(int $amount, string $sku, string $name)
    {
        $this->amount = $amount;
        $this->sku = $sku;
        $this->name = $name;
    }
}
