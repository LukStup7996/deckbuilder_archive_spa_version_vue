<?php

namespace deckbuilder_archive_spa_version_vue\api\config;

class JwtHelper {
    private static $secretKey = 'your_secret_key'; // Change this to your actual secret key
    private static $algorithm = 'HS256';
    private static $issuer = 'your_domain.com';

    public static function createJwt($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        
        $payload['iss'] = self::$issuer;
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600;
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        
        return $jwt;
    }

    public static function validateJwt($jwt) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) {
            return false;
        }
        
        $header = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[0]));
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1]));
        $signatureProvided = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[2]));
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signatureExpected = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        
        if (hash_equals($signatureProvided, $signatureExpected)) {
            $payloadArray = json_decode($payload, true);
            if ($payloadArray['iss'] !== self::$issuer || $payloadArray['exp'] < time()) {
                return false;
            }
            return $payloadArray;
        }
        
        return false;
    }
}
