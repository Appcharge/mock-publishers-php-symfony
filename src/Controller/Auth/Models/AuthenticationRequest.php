<?php

namespace App\Controller\Auth\Models;

use DateTime;

class AuthenticationRequest
{
    public string $authMethod = '';
    public string $authType = '';
    public string $token = '';
    public DateTime $date;
    public string $appId = '';
    public string $publisherToken = '';

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public static function from_json($data): AuthenticationRequest
    {
        $request = new AuthenticationRequest();
        $request->authMethod = $data['authMethod'] ?? '';
        $request->authType = $data['authType'] ?? '';
        $request->token = $data['token'] ?? '';
        $request->date = isset($data['date']) ? new DateTime($data['date']) : new DateTime();
        $request->appId = $data['appId'] ?? '';
        $request->publisherToken = $data['publisherToken'] ?? '';
        return $request;
    }
}
