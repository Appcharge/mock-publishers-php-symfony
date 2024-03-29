<?php

namespace App\Utility;

class SignatureUtility
{
    public static function parseSignature($signatureString)
    {
        $regex = '/t=(.*),v1=(.*)/';
        preg_match($regex, $signatureString, $matches);

        if (!isset($matches[0]) || count($matches) < 3) {
            throw new \InvalidArgumentException('Invalid signature format');
        }

        $t = $matches[1];
        $v1 = $matches[2];

        return (object) ['T' => $t, 'V1' => $v1];
    }

    public static function signPayload($expectedSignatureHeader, $data, $key)
    {
        $expectedSignature = self::parseSignature($expectedSignatureHeader);
        $dataBytes = utf8_encode("{$expectedSignature->T}.{$data}");
        $hmacBytes = hash_hmac('sha256', $dataBytes, $key, true);
        $signature = strtolower(bin2hex($hmacBytes));

        return array('signature' => $signature, 'expectedSignature' => $expectedSignature->V1);
    }

}
