<?php


function decrypt($enc_string, $iv, $key) {
    $body = base64_encode(hex2bin($enc_string));
    $dec = openssl_decrypt($body, "AES-256-CBC", $key, 0, $iv);
    
    return $dec;
}

?>