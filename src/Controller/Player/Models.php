<?php

namespace App\Controller\Player;
use DateTime;


class PlayerUpdateRequest
{
    public string $appChargePaymentId;
    public DateTime $purchaseDateAndTimeUtc;
    public string $gameId;
    public string $playerId;
    public string $bundleName;
    public string $bundleId;
    public string $sku;
    public int $priceInCents;
    public float $priceInDollar;
    public string $currency;
    public float $tax;
    public float $subTotal;
    public string $action;
    public string $actionStatus;
    public array $products;

    public function __construct(
        string $appChargePaymentId,
        DateTime $purchaseDateAndTimeUtc,
        string $gameId,
        string $playerId,
        string $bundleName,
        string $bundleId,
        string $sku,
        int $priceInCents,
        float $priceInDollar,
        float $tax,
        float $subTotal,
        string $currency,
        string $action,
        string $actionStatus,
        array $products,
    ) {
        $this->appChargePaymentId = $appChargePaymentId;
        $this->purchaseDateAndTimeUtc = $purchaseDateAndTimeUtc;
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->bundleName = $bundleName;
        $this->bundleId = $bundleId;
        $this->sku = $sku;
        $this->priceInCents = $priceInCents;
        $this->priceInDollar = $priceInDollar;
        $this->currency = $currency;
        $this->tax = $tax;
        $this->subTotal = $subTotal;
        $this->action = $action;
        $this->actionStatus = $actionStatus;
        $this->products = $products;
    }

    public static function from_json($data): self
    {
        return new self(
            $data['appChargePaymentId'],
            new DateTime($data['purchaseDateAndTimeUtc']),
            $data['gameId'],
            $data['playerId'],
            $data['bundleName'],
            $data['bundleId'],
            $data['sku'],
            $data['priceInCents'],
            $data['priceInDollar'],
            $data['tax'],
            $data['subTotal'],
            $data['currency'],
            $data['action'],
            $data['actionStatus'],
            array_map(function ($product_data) {
                return new Product($product_data['amount'], $product_data['sku'], $product_data['name']);
            }, $data['products']),
        );
    }
}

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


class PlayerUpdateBalanceResponse {
    public string $publisherPurchaseId;

    public function __construct($publisherPurchaseId) {
        $this->publisherPurchaseId = $publisherPurchaseId;
    }

}


?>