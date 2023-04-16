<?php

namespace App\Controller\Auth;
use DateTime;

class AuthResult {
    public $is_valid;
    public $user_id;

    public function __construct($is_valid, $user_id) {
        $this->is_valid = $is_valid;
        $this->user_id = $user_id;
    }
}

class AuthenticationRequest {
    public string $authMethod = '';
    public string $authType = '';
    public string $token = '';
    public DateTime $date;
    public string $appId = '';
    public string $publisherToken = '';

    public function __construct() {
        $this->date = new DateTime();
    }

    public static function from_json($data): AuthenticationRequest {
        $request = new AuthenticationRequest();
        $request->authMethod = $data->authMethod ?? '';
        $request->authType = $data->authType ?? '';
        $request->token = $data->token ?? '';
        $request->date = new DateTime($data->date ?? '');
        $request->appId = $data->appId ?? '';
        $request->publisherToken = $data->publisherToken ?? '';
        return $request;
    }
}

class ItemBalance {
    public string $currency;
    public int $balance;

    public function __construct(string $currency, int $balance) {
        $this->currency = $currency;
        $this->balance = $balance;
    }
}

class AuthResponse {
    public string $status;
    public string $playerProfileImage;
    public string $publisherPlayerId;
    public string $playerName;
    public array $segments;
    public array $balances;

    public function __construct(string $status, string $playerProfileImage, string $publisherPlayerId, string $playerName, array $segments, array $balances)
    {
        $this->status = $status;
        $this->playerProfileImage = $playerProfileImage;
        $this->publisherPlayerId = $publisherPlayerId;
        $this->playerName = $playerName;
        $this->segments = $segments;
        $this->balances = $balances;
    }
}


?>