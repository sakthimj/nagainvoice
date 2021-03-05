<?php
function decryptBySymmetricKey($encSekB64, $appKey)
{
  $sek = openssl_decrypt($encSekB64, "aes-256-ecb", base64_decode($appKey), 0);
  $sekB64 = base64_encode($sek);
  return $sekB64;
}
function encryptBySymmetricKey($irn_data, $sekB64)
{
  $data = $irn_data;
  $sek = base64_decode($sekB64);
  $encDataB64 = openssl_encrypt($data, "aes-256-ecb", $sek, 0);
  return $encDataB64;
}
include_once("config.php");
$data = "Naga$2020";
$key = file_get_contents("./einv_sandbox.pem");
openssl_public_encrypt($data, $output, $key);
$de = base64_encode($output);
//echo $de . "\n";
//$appKey = openssl_random_pseudo_bytes(32);
$appKey = "p7vRArKHQeUyiRPz6ZXvzjpcVC2Yvu6HZkkL99FtC7I=";
//echo $appKey . "\n";
openssl_public_encrypt($appKey, $output1, $key);
$de1 = base64_encode($output1);
//echo $de1;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://einv-apisandbox.nic.in/eivital/v1.03/auth',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{
    "data": {
      "UserName": "naga_ltd",
      "Password": "' . $de . '",
      "AppKey": "En7NzgYluXIi8HLMlKmL6N9ur7M/7FMG5HpAVzuFLbPtf+3f2L8Nt6kz8wX2Wry3fXh2fvyyUPcohZvEFGMCCppbFlNLPUuq7bbk4gvEreM+lQzce1/Vk0AAYIbQ0dkm6Q6ELXDjAq2yeYfmgb9sFRjI8onA3ZkTgKTsIsxxsT/XZoz61Xv6DVIbXBJpdoTM3Zzh05wJcjDtu2gEKAObd5Tl70rw0PchL56b0w/AqYsir986j3OGeDMueD4gGB/nArzJ2ikH3bEQh2ukDYJDjFEgnk/3arxB5oFY2iRHu1xtaFwQ2Fsk3oNAxKou1hFEhraEkQxjkli7t4l27yxTqw==",
      "ForceRefreshAccessToken": true
    }
  }',
  CURLOPT_HTTPHEADER => array(
    'client_id: AAACN33TXPB09Q4',
    'client_secret: 3SGgUuA9Oocq7WKk1yEs',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
$data = json_decode($response, true);
$AuthToken = $data['Data']['AuthToken'];
$Sek = $data['Data']['Sek'];
$TokenExpiry = $data['Data']['TokenExpiry'];

$appKey = 'p7vRArKHQeUyiRPz6ZXvzjpcVC2Yvu6HZkkL99FtC7I=';
$encSekB64 = $Sek;
$sekB64 = decryptBySymmetricKey($encSekB64, $appKey);
echo $sekB64 . "\n";

$irn_data = '{
    "Irn": "38d8ad2e74afc42467f794fb3d68ba7171a791c7c8abb237743a6a7fc4191841",
    "CnlRsn": "1",
    "CnlRem": "cancel Remarks"
  }';
$encDataB64 = encryptBySymmetricKey($irn_data, $sekB64);
echo $encDataB64;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://einv-apisandbox.nic.in/eicore/v1.03/Invoice/Cancel',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{
  "Data": "' . $encDataB64 . '"}',
  CURLOPT_HTTPHEADER => array(
    'client_id: AAACN33TXPB09Q4',
    'client_secret: 3SGgUuA9Oocq7WKk1yEs',
    'Gstin: 33AAACN2369L1ZD',
    "authtoken: $AuthToken",
    'user_name: naga_ltd',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
