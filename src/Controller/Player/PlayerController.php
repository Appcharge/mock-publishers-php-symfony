<?php

namespace App\Controller\Player;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    public function updateBalance(Request $request): JsonResponse
    {
        $requestBody = $request->getContent();
        $headers = $request->headers->all();

        if (isset($headers['signature'])) {
            $serializedJson = json_encode(json_decode($requestBody));
            signPayload($headers['signature'][0], $serializedJson);
        } else {
            $requestBody = decrypt($requestBody);
        }

        $playerUpdate = PlayerUpdateRequest::from_json(json_decode($requestBody, true));
        $response = $this->json(new PlayerUpdateBalanceResponse("<PURCHASE-ID>"));
        return $response;
    }
}

?>