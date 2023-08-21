<?php

namespace App\Controller\Auth\Models;

class ItemBalance
{
    public string $currency;
    public int $balance;

    public function __construct(string $currency, int $balance)
    {
        $this->currency = $currency;
        $this->balance = $balance;
    }
}
