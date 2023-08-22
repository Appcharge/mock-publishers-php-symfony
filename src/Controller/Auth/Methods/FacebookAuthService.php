<?php
namespace App\Controller\Auth\Methods;

use App\Controller\Auth\Models\AuthResult;
use GuzzleHttp\Client;

class FacebookAuthService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function login($appId, $token, $appSecret): AuthResult
    {
        $url = "https://graph.facebook.com/debug_token?input_token=$token&access_token=$appId|$appSecret";

        try {
            $response = $this->client->get($url);

            if ($response->getStatusCode() == 200) {
                $responseString = $response->getBody()->getContents();
                $responseObj = json_decode($responseString);
                $is_valid = $responseObj->data->is_valid;
                $user_id = $responseObj->data->user_id;

                return new AuthResult($is_valid, $user_id);
            }

        } catch (ClientException $ex) {}

        return new AuthResult(false, "0");
    }
}
