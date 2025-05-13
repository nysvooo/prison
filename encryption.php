<?php
// Encryption and decryption key
define('ENCRYPTION_KEY', 'your_secret_key_here'); // Replace this with a strong secret key
define('ENCRYPTION_METHOD', 'AES-256-CBC'); // Encryption method

// Function to encrypt data
function encryptData($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD)); // Generate a random IV
    $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv); // Encrypt the data
    return base64_encode($encrypted . '::' . $iv); // Return the encrypted data and IV encoded in base64
}

// Function to decrypt data
function decryptData($data) {
    // Check if data is empty or in an unexpected format
    if (empty($data) || strpos($data, '::') === false) {
        return ''; // Return empty if the data is not in the expected format
    }

    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2); // Separate encrypted data and IV

    // If the IV is not available, return an error message or empty string
    if (empty($iv)) {
        return ''; // Return empty or handle this case as needed
    }

    // Decrypt the data
    return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
}
?>