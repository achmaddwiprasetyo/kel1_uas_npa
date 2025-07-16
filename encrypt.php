<?php
define('AES_KEY', 'iniKunciAES32Karakter123456789012'); //AES-256

function encryptFile($source, $dest, $key)
{
    $data = file_get_contents($source);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    $output = $iv . $encrypted;
    file_put_contents($dest, $output);
}

function decryptFile($source, $dest, $key)
{
    $data = file_get_contents($source);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    file_put_contents($dest, $decrypted);
}
