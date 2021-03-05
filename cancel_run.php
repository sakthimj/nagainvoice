<?php
function decryptBySymmetricKey($encSekB64, $appKey)
{
    $sek = openssl_decrypt($encSekB64, "aes-256-ecb", base64_decode($appKey), 0);
    $sekB64 = base64_encode($sek);
    return $sekB64;
}
function encryptBySymmetricKey($eway, $sekB64)
{
    $data = $eway;
    //echo $eway;
    $sek = base64_decode($sekB64);
    $encDataB64 = openssl_encrypt($data, "aes-256-ecb", $sek, 0);
    return $encDataB64;
}
function decryptBySymmetricKeyi($data_irn, $sekB64)
{
    $data = $data_irn;
    $sek = base64_decode($sekB64);
    $irn_de = openssl_decrypt($data, "aes-256-ecb", $sek, 0);
    return $irn_de;
}
function EinvoiceFromDubIRN($Data_dub_irn, $sekB64)
{
    $data = $Data_dub_irn;
    $sek = base64_decode($sekB64);
    $irn_de = openssl_decrypt($data, "aes-256-ecb", $sek, 0);
    return $irn_de;
}
function encryptBySymmetricKeyIRN($irn_data, $sekB64)
{
    $data = $irn_data;
    $sek = base64_decode($sekB64);
    $encDataB64 = openssl_encrypt($data, "aes-256-ecb", $sek, 0);
    return $encDataB64;
}
if ($handle = opendir('./cancel')) {

    while (false !== ($einvoice_cancel = readdir($handle))) {

        if ($einvoice_cancel != "." && $einvoice_cancel != "..") {
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
            $AuthToken = "";
            $Sek = "";
            $TokenExpiry = "";
            $AuthToken = $data['Data']['AuthToken'];
            $Sek = $data['Data']['Sek'];
            $TokenExpiry = $data['Data']['TokenExpiry'];


            $appKey = 'p7vRArKHQeUyiRPz6ZXvzjpcVC2Yvu6HZkkL99FtC7I=';
            $encSekB64 = $Sek;
            $sekB64 = decryptBySymmetricKey($encSekB64, $appKey);
            //echo $sekB64 . "\n";

            $query = "INSERT INTO token(AuthToken,Sek,TokenExpiry) value ('$AuthToken','$sekB64','$TokenExpiry')";
            mysqli_query($connect, $query);
            //echo $query;
            $cancel_invoice = file_get_contents("./cancel/" . $einvoice_cancel);
            $invoice_no = "SELECT * From einvoice where invoice_no = " . $cancel_invoice . " ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($connect, $invoice_no);
            $row = mysqli_fetch_array($result);
            $cancel_irn_no = $row['Irn'];

            $eway = '{
                "ewbNo": ' . $row['EwbNo'] . ',
                "cancelRsnCode": 2,
                "cancelRmrk": "Cancelled the order"
              }';
            $enc_invoice = base64_encode($eway);
            echo $eway;
            $encDataB64 = encryptBySymmetricKey($eway, $sekB64);
            //unlink("./invoice/" . $entry_invoice_json);
            echo $encDataB64;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://einv-apisandbox.nic.in/ewaybillapi/v1.03/ewayapi',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "action": "CANEWB",
                    "data": "' . $encDataB64 . '"
                  }',
                CURLOPT_HTTPHEADER => array(
                    'client_id: AAACN33TXPB09Q4',
                    'client_secret: 3SGgUuA9Oocq7WKk1yEs',
                    "AuthToken: $AuthToken",
                    "Sek: $sekB64",
                    'user_name: naga_ltd',
                    'Gstin: 33AAACN2369L1ZD',
                    'Content-Type: application/json'
                ),
            ));

            $response_irn = curl_exec($curl);

            curl_close($curl);
            echo $response_irn;
            $mysql_insert = json_decode($response_irn, true);
            //date_default_timezone_set('Asia/Calcutta');
            //$date = date("Y-m-d H:i:s");
            $data[] = $mysql_insert;
            $data = array();
            array_push($data, $mysql_insert);
            foreach ($data as $row) {
                $status = $row['status'];
                if ($status == 1) {
                    // Json Dec
                    $data_irn = $row['data'];
                    $irn_de = decryptBySymmetricKeyi($data_irn, $sekB64);
                    //echo $irn_de;
                    $irn_res = json_decode($irn_de, true);
                    $data_irn_res[] = $irn_res;
                    $data_irn_res = array();
                    array_push($data_irn_res, $irn_res);
                    foreach ($data_irn_res as $row) {
                        $ewayBillNo = $row['ewayBillNo'];
                        $cancelDate = $row['cancelDate'];
                        $update = "UPDATE einvoice SET ewaybill_cancel_date = '$cancelDate' WHERE invoice_no = " . $cancel_invoice . "";
                        mysqli_query($connect, $update);
                        //echo $update;
                    }
                    $irn_data = '{
                        "Irn": "' . $cancel_irn_no . '",
                        "CnlRsn": "1",
                        "CnlRem": "cancel Remarks"
                      }';
                    $encDataB64 = encryptBySymmetricKeyIRN($irn_data, $sekB64);
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

                    $response_irn_cancel = curl_exec($curl);

                    curl_close($curl);
                    echo $response_irn_cancel;
                    $mysql_insert1 = json_decode($response_irn_cancel, true);
                    //date_default_timezone_set('Asia/Calcutta');
                    //$date = date("Y-m-d H:i:s");
                    $data1[] = $mysql_insert1;
                    $data1 = array();
                    array_push($data1, $mysql_insert1);
                    foreach ($data1 as $row1) {
                        $status1 = $row1['Status'];
                        if ($status1 == 1) {
                            // Json Dec
                            $data_irn1 = $row1['Data'];
                            $irn_de1 = decryptBySymmetricKeyi($data_irn1, $sekB64);
                            $irn_res1 = json_decode($irn_de1, true);
                            $data_irn_res1[] = $irn_res1;
                            $data_irn_res1 = array();
                            array_push($data_irn_res1, $irn_res1);
                            foreach ($data_irn_res1 as $row1) {
                                $cancelDate_irn = $row1['CancelDate'];
                                echo $cancelDate_irn;
                                $update_irn = "UPDATE einvoice SET irn_cancel_date = '$cancelDate_irn' WHERE invoice_no = " . $cancel_invoice . "";
                                mysqli_query($connect, $update_irn);
                                echo $update;
                            }
                        }
                    }
                } else {
                    include_once("./assets/page/sms_invoice_cancel_api.php");
                }
            }
        }
    }
    closedir($handle);
}
