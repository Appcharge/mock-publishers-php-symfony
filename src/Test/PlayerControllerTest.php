<?php

use App\Controller\Player\PlayerController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class PlayerControllerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        putenv('KEY=test_key');
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testUpdateBalanceSuccess()
    {
        $mockedSignature = 't=1692625709429,v1=6bbe0915205169ef67107c41f99006a25656b18fd6f4fa1fa4093c9182eeeab0';

        $mockRequestContent = json_encode([
            "appChargePaymentId" => "test",
            "purchaseDateAndTimeUtc" => "2023-08-04T11:51:19.999Z",
            "gameId" => "123",
            "playerId" => "1234a",
            "bundleName" => "test",
            "bundleId" => "testId",
            "sku" => "15",
            "priceInCents" => 1500,
            "currency" => "USD",
            "priceInDollar" => 15,
            "action" => "test",
            "actionStatus" => "completed",
            "tax" => 15,
            "subTotal" => 150,
            "products" => [],
        ]);

        $request = new Request([], [], [], [], [], [], $mockRequestContent);
        $request->headers->set('signature', $mockedSignature);

        $controller = new PlayerController();
        $response = $controller->updateBalance($request);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('<PURCHASE-ID>', $responseData['publisherPurchaseId']);
    }

    public function testUpdateBalanceInvalidSignature()
    {
        $mockRequestContent = json_encode([
            'playerId' => '1234',
            'amount' => 10,
        ]);

        $request = new Request([], [], [], [], [], [], $mockRequestContent);
        $request->headers->set('signature', 't=1691145170939,v1=invalid');

        $controller = new PlayerController();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Signatures don't match");

        $controller->updateBalance($request);
    }
}
