<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Auth\Methods\facebook;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Exception;


global $FACEBOOK_APP_SECRET;
$FACEBOOK_APP_SECRET = getenv('FACEBOOK_APP_SECRET');

class AuthController extends AbstractController
{
    public function auth(Request $request)
    {
        global $FACEBOOK_APP_SECRET;

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
        
        $auth_request = AuthenticationRequest::from_json(json_decode($requestBody, true));
        $auth_method = $auth_request->authMethod;

        $auth_result = new AuthResult(false, '');
        switch($auth_method) {
            case 'facebook':
                $auth_result = facebook_login($auth_request->appId, $auth_request->token, $FACEBOOK_APP_SECRET);
                break;
            default:
                fwrite(STDOUT, "Authentication method of type '" . $auth_request->authMethod . "' is not supported");
                return new Response("Authentication method of type '" . $auth_request->authMethod . "' is not supported", Response::HTTP_BAD_REQUEST);
        }
        
        if($auth_result->is_valid) {
            $user_id = $auth_result->user_id;
            
            $balances = array(new ItemBalance("diamonds", 15));
            $segments = array("seg1", "seg2");
            $response = new AuthResponse("valid", "<player profile image>", "<player id>", "<player name>", $segments, $balances);
            
            $response_json = $this->json($response);
            
            return $response_json;
        } else {
            fwrite(STDOUT, "Authentication failed");
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}

?>