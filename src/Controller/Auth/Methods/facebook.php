<?php
use App\Controller\Auth\AuthResult;
use GuzzleHttp\Exception\ClientException;

if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'wb'));

function log_console($str){
    fwrite(STDOUT, $str . "\n"); 
}

function facebook_login($appId, $token, $appSecret) {
    $url = "https://graph.facebook.com/debug_token?input_token=$token&access_token=$appId|$appSecret";
    $client = new \GuzzleHttp\Client();

    try {
        $response = $client->get($url);

        log_console("auth facebook");
        log_console("status = " . $response->getStatusCode());
    
        if ($response->getStatusCode() == 200) {
            // log_console(strval($response->getBody()->getContents()));
    
            $responseString = $response->getBody()->getContents();
            $responseObj = json_decode($responseString);
            $is_valid = $responseObj->data->is_valid;
            $user_id = $responseObj->data->user_id;
    
            return new AuthResult($is_valid, $user_id);
        }

    } catch (ClientException $ex) {
        // Login failed
    }

    return new AuthResult(false, "0");

}
?>