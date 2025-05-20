<?php
session_start();

// Token Gen & Methods
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getCsrfToken() {
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return hash_equals($_SESSION['csrf_token'], $token ?? '');
}

// Loads your key
function loadEncryptionKey($path = '/etc/myapp.key') {
    if (!file_exists($path)) {
        throw new Exception("Encryption key file not found.");
    }
    return file_get_contents($path);
}


// Encryption & Decryption
function secureEncrypt($plaintext, $key) {
    $ivLength = openssl_cipher_iv_length('aes-256-gcm');
    $iv = random_bytes($ivLength);
    $tag = '';
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    return base64_encode($iv . $tag . $ciphertext);
}

function secureDecrypt($encoded, $key) {
    $data = base64_decode($encoded);
    $ivLength = openssl_cipher_iv_length('aes-256-gcm');
    $iv = substr($data, 0, $ivLength);
    $tag = substr($data, $ivLength, 16); 
    $ciphertext = substr($data, $ivLength + 16);
    return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
}
?>
