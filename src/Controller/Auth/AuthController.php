<?php

namespace App\Controller\Auth;

use App\Controller\Auth\Methods\FacebookAuthService;
use App\Controller\Auth\Models\AuthenticationRequest;
use App\Controller\Auth\Models\AuthResponse;
use App\Controller\Auth\Models\AuthResult;
use App\Controller\Auth\Models\ItemBalance;
use App\Utility\SignatureUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

global $FACEBOOK_APP_SECRET;
$FACEBOOK_APP_SECRET = getenv('FACEBOOK_APP_SECRET');

class AuthController extends AbstractController
{
    private $facebookAuthService;

    public function __construct(FacebookAuthService $facebookAuthService)
    {
        $this->facebookAuthService = $facebookAuthService;
    }

    public function auth(Request $request)
    {
        global $FACEBOOK_APP_SECRET;

        $requestBody = $request->getContent();
        $headers = $request->headers->all();

        $key = getenv('KEY');

        $serializedJson = json_encode(json_decode($requestBody));
        $result = SignatureUtility::signPayload($headers['signature'][0], $serializedJson, $key);
        if ($result['signature'] !== $result['expectedSignature']) {
            throw new \Exception("Signatures don't match");
        }

        $auth_request = AuthenticationRequest::from_json(json_decode($requestBody, true));
        $auth_method = $auth_request->authMethod;

        $auth_result = new AuthResult(false, '');
        switch ($auth_method) {
            case 'facebook':
                $auth_result = $this->facebookAuthService->login($auth_request->appId, $auth_request->token, $FACEBOOK_APP_SECRET);
                break;
            default:
                return new Response("Authentication method of type '" . $auth_request->authMethod . "' is not supported", Response::HTTP_BAD_REQUEST);
        }

        if ($auth_result->is_valid) {
            $user_id = $auth_result->user_id;

            $balances = array(new ItemBalance("diamonds", 15));
            $segments = array("seg1", "seg2");
            $response = new AuthResponse("valid", "<player profile image>", "<player id>", "<player name>", $segments, $balances);

            $response = new JsonResponse($response);
            return $response;
        } else {
            return new Response("Authentication failed", Response::HTTP_BAD_REQUEST);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
