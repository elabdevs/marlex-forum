<?php
namespace App\Models;

use Exception;

class Crypto {
    private static $cipher = 'aes-256-cbc';

    public static function encrypt($data, $key = 'T9v*Lp6&gQr') {
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        $encrypted = openssl_encrypt($data, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new Exception('Encryption failed.');
        }

        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($data, $key = 'T9v*Lp6&gQr') {
        $data = base64_decode($data);
        $ivlen = openssl_cipher_iv_length(self::$cipher);

        $iv = substr($data, 0, $ivlen);
        $encrypted_data = substr($data, $ivlen);

        $decrypted = openssl_decrypt($encrypted_data, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new Exception('Decryption failed.');
        }

        return $decrypted;
    }
}
