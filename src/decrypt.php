<?php


function decrypt($enc_string) {
    $iv = getenv('IV');
    $key = getenv('KEY');
    
    $body = base64_encode(hex2bin($enc_string));
    $dec = openssl_decrypt($body, "AES-256-CBC", $key, 0, $iv);
    
    return $dec;
}

?>