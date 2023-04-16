<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Auth\Methods\facebook;
use DateTime;
use Symfony\Component\HttpFoundation\Response;


global $FACEBOOK_APP_SECRET;
$FACEBOOK_APP_SECRET = getenv('FACEBOOK_APP_SECRET');

class AuthController extends AbstractController
{
    public function auth(Request $request)
    {
        global $FACEBOOK_APP_SECRET;

        $body = decrypt($request->getContent());
        $body = json_decode($body);
        
        $auth_request = AuthenticationRequest::from_json($body);
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