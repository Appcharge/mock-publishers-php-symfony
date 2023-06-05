<?php

namespace App\Controller\Player;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class PlayerController extends AbstractController
{
    public function updateBalance(Request $request): JsonResponse
    {
        $requestBody = $request->getContent();
        $headers = $request->headers->all();

        $iv = getenv('IV');
        $key = getenv('KEY');

        if (isset($headers['signature'])) {
            $serializedJson = json_encode(json_decode($requestBody));
            $result = signPayload($headers['signature'][0], $serializedJson, $key);
            if ($result['signature'] !== $result['expectedSignature']) {
                throw new \Exception("Signatures don't match");
            }
        } else {
            $requestBody = decrypt($requestBody, $iv, $key);
        }

        $playerUpdate = PlayerUpdateRequest::from_json(json_decode($requestBody, true));
        $response = $this->json(new PlayerUpdateBalanceResponse("<PURCHASE-ID>"));
        return $response;
    }
}

?>