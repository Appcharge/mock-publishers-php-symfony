<?php

namespace App\Controller\Player\Models;

class PlayerUpdateBalanceResponse
{
    public string $publisherPurchaseId;

    public function __construct($publisherPurchaseId)
    {
        $this->publisherPurchaseId = $publisherPurchaseId;
    }

}
