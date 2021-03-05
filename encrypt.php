<?php
/* $data = "Naga$2020";
$key = file_get_contents("./einv_sandbox.pem");
openssl_public_encrypt($data, $output, $key);
$de = base64_encode($output); */
//echo $de . "\n";

/* $appkey = "FGqE3xKe2uSmVoeE/1n0hvMUL/sQ34q2t50cVeSkV14=";

$data1 = "/GCLbzYZ4V7X+/c4EZ97a1a8WKwBhax1eWpMsF5yWjp5rAuliDzspmCTwrM/ukSS";

define('AES_256_CBC', 'aes-256-cbc');
$decrypted = openssl_decrypt($data1, AES_256_CBC, $appkey, 0);
echo "Decrypted:" . base64_encode($decrypted) . "\n"; */


// Store a string into the variable which 
// need to be Encrypted 
//$simple_string = "WwsnBRJrIxflsLGwFtIBHrp6lzzN5ywOl4oYK2IjjGA=";

// Display the original string 
//echo "Original String: " . $simple_string;

// Store the cipher method 
$ciphering = "aes-256-ecb";

// Use OpenSSl Encryption method 
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;

// Non-NULL Initialization Vector for encryption 
//$encryption_iv = '1234567891011121';

// Store the encryption key 
//$encryption_key = "FGqE3xKe2uSmVoeE/1n0hvMUL/sQ34q2t50cVeSkV14=";

// Use openssl_encrypt() function to encrypt the data 
/* $encryption = openssl_encrypt(
    $simple_string,
    $ciphering,
    $encryption_key,
    $options,
    $encryption_iv
); */

// Display the encrypted string 
//echo "Encrypted String: " . $encryption . "\n";

$encryption = base64_decode("sND/4hhyEI18HDVxCb7IGv3/p++ladnE3UF4BNo4lgw4lgp2Xt/p3geAX+v2NW15");

// Non-NULL Initialization Vector for decryption 
//$decryption_iv = hex2bin('1234567891011121');

// Store the decryption key 
$decryption_key = "4ek85BbINHlQs9Wy5ba4d7dYTaACGG2pOdd0ciUaJj0=";

// Use openssl_decrypt() function to decrypt the data 
$decryption = openssl_decrypt(
    $encryption,
    $ciphering,
    $decryption_key,
    $options
);

// Display the decrypted string 
echo "Decrypted String: " . base64_encode($decryption);
