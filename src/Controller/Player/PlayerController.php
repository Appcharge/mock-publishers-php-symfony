<?php

namespace App\Controller\Player;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    public function updateBalance(Request $request): JsonResponse
    {
        $body = decrypt($request->getContent());

        $playerUpdate = PlayerUpdateRequest::from_json($body);
        $response = $this->json(new PlayerUpdateBalanceResponse("<PURCHASE-ID>"));
        return $response;
    }
}

?>