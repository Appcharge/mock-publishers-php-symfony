<?php

namespace App\Controller\Player;

use App\Controller\Player\Models\PlayerUpdateBalanceResponse;
use App\Controller\Player\Models\PlayerUpdateRequest;
use App\Utility\SignatureUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    public function updateBalance(Request $request): JsonResponse
    {
        $requestBody = $request->getContent();
        $headers = $request->headers->all();

        $key = getenv('KEY');

        $serializedJson = json_encode(json_decode($requestBody));
        $result = SignatureUtility::signPayload($headers['signature'][0], $serializedJson, $key);
        if ($result['signature'] !== $result['expectedSignature']) {
            throw new \Exception("Signatures don't match");
        }

        $playerUpdate = PlayerUpdateRequest::from_json(json_decode($requestBody, true));
        $response = new JsonResponse(new PlayerUpdateBalanceResponse("<PURCHASE-ID>"));
        return $response;
    }
}
