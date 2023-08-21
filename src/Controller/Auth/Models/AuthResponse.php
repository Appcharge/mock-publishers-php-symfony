<?php

namespace App\Controller\Auth\Models;

class AuthResponse
{
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
