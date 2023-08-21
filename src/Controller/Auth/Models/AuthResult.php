<?php

namespace App\Controller\Auth\Models;

class AuthResult
{
    public $is_valid;
    public $user_id;

    public function __construct($is_valid, $user_id)
    {
        $this->is_valid = $is_valid;
        $this->user_id = $user_id;
    }
}
