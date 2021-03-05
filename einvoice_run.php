<?php
function decryptBySymmetricKey($encSekB64, $appKey)
{
    $sek = openssl_decrypt($encSekB64, "aes-256-ecb", base64_decode($appKey), 0);
    $sekB64 = base64_encode($sek);
    return $sekB64;
}
function encryptBySymmetricKey($invoice, $sekB64)
{
    $data = $invoice;
    //echo $data;
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
if ($handle = opendir('./invoice')) {

    while (false !== ($entry_invoice_json = readdir($handle))) {

        if ($entry_invoice_json != "." && $entry_invoice_json != "..") {
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

            //IRN GEN


            $invoice = json_decode(file_get_contents("./invoice/" . $entry_invoice_json), true);
            $invoice_final = json_encode($invoice, JSON_PRETTY_PRINT);
            $invoice_trim = ltrim($invoice_final, "[");
            $invoice_trim_right = rtrim($invoice_trim, "]");
            $remove_dis = str_replace('"distance": 1', '"distance": 0', $invoice_trim_right);
            $enc_invoice = base64_encode($remove_dis);
            //echo $remove_dis;
            $encDataB64 = encryptBySymmetricKey($remove_dis, $sekB64);
            //unlink("./invoice/" . $entry_invoice_json);
            //echo $encDataB64;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://einv-apisandbox.nic.in/eicore/v1.03/Invoice',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{"Data":"' . $encDataB64 . '"}',
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
            //echo $response_irn;

            $mysql_insert = json_decode($response_irn, true);
            //date_default_timezone_set('Asia/Calcutta');
            //$date = date("Y-m-d H:i:s");
            $data[] = $mysql_insert;
            $data = array();
            array_push($data, $mysql_insert);
            foreach ($data as $row) {
                $status = $row['Status'];
                if ($status == 1) {
                    // Json Dec
                    $data_irn = $row['Data'];
                    $irn_de = decryptBySymmetricKeyi($data_irn, $sekB64);
                    //echo $irn_de;
                    $irn_res = json_decode($irn_de, true);
                    $data_irn_res[] = $irn_res;
                    $data_irn_res = array();
                    array_push($data_irn_res, $irn_res);
                    foreach ($data_irn_res as $row) {
                        $AckNo = $row['AckNo'];
                        $AckDt = $row['AckDt'];
                        $Irn = $row['Irn'];
                        $SignedInvoice = $row['SignedInvoice'];
                        $SignedQRCode = $row['SignedQRCode'];
                        $Status = $row['Status'];
                        $EwbNo = $row['EwbNo'];
                        $EwbDt = $row['EwbDt'];
                        $EwbValidTill = $row['EwbValidTill'];
                        $Remarks = $row['Remarks'];
                        //echo $query;
                        $invoice_json = json_decode($invoice_trim_right, true);
                        $invoice_number = $invoice_json['docDtls']['no'];
                        $invoice_date = $invoice_json['docDtls']['dt'];
                        $invoice_type = "Tax Invoice";
                        $invoice_number = $invoice_json['docDtls']['no'];
                        $invoice_date = $invoice_json['docDtls']['dt'];
                        $invoice_type = "Tax Invoice";
                        $invoice_value = $invoice_json['valDtls']['totInvVal'];
                        $invoice_gst = $invoice_json['buyerDtls']['gstin'];
                        $customer_name = $invoice_json['buyerDtls']['lglNm'];
                        $value_amt = $invoice_json['valDtls']['totInvVal'];
                        $query = "INSERT INTO einvoice(AckNo,AckDt,Irn,SignedInvoice,SignedQRCode,Status,EwbNo,EwbDt,EwbValidTill,Remarks,invoice_no,invoice_date,customer_gst,customer_name,value_amt) value ('$AckNo','$AckDt','$Irn','$SignedInvoice','$SignedQRCode','$Status','$EwbNo','$EwbDt','$EwbValidTill','$Remarks','$invoice_number','$invoice_date','$invoice_gst','$customer_name','$value_amt')";
                        mysqli_query($connect, $query);
                        $output = "Sl. No" . "\t" . "IRN" . "\t" . "Ack No" . "\t" . "Ack Date" . "\t" . "Doc No" . "\t" . "Doc Typ" . "\t" . "Doc Date" . "\t" . "Inv Value." . "\t" . "Recipient GSTIN" . "\t" . "Status" . "\t" . "Signed QR Code" . "\t" . "EWB No./ If Any Errors While Creating EWB." . "\n" . "1" . "\t" . "$Irn" . "\t" . "$AckNo" . "\t" . "$AckDt" . "\t" . "$invoice_number"
                            . "\t" . "$invoice_type" . "\t" . "$invoice_date" . "\t" . "$invoice_value" . "\t" . "$invoice_gst" . "\t" . "" . "\t" . "$SignedQRCode" . "\t" . "$EwbNo" . "\n";
                        //echo $output;
                        file_put_contents("ewaybill.txt", $output . PHP_EOL, FILE_APPEND | LOCK_EX);
                    }
                } else {
                    foreach ($row['ErrorDetails'] as $sms1) {
                        $invoice_json = json_decode($invoice_trim_right, true);
                        $invoice_number = $invoice_json['docDtls']['no'];
                        $Errorcode = $sms1['ErrorCode'];
                        $ErrorMessage = $sms1['ErrorMessage'];
                        include_once("./assets/page/sms_send_api.php");
                    }
                    if ($Errorcode == 2278) {
                        /* 
                        foreach ($row['InfoDtls'] as $row1) {
                             $InfCd = $row1['InfCd'];
            $AckNo = $row1['Desc']['AckNo'];
            $AckDt = $row1['Desc']['AckDt'];
                            $Irn = $row1['Desc']['Irn'];
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://einv-apisandbox.nic.in/eicore/v1.03/Invoice/irn/' . "$Irn",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                                CURLOPT_HTTPHEADER => array(
                                    'client_id: AAACN33TXPB09Q4',
                                    'client_secret: 3SGgUuA9Oocq7WKk1yEs',
                                    'Gstin: 33AAACN2369L1ZD',
                                    'user_name: naga_ltd',
                                    "AuthToken: $AuthToken",
                                ),
                            ));
                            $response_irn_to_ewaybill = curl_exec($curl);
                            curl_close($curl);
                            //echo $response_irn_to_ewaybill;
                            $data_irn_to_ewaybill = json_decode($response_irn_to_ewaybill, true);
                            $Data_dub_irn = $data_irn_to_ewaybill['Data'];
                            $Irn_Ewaybill = EinvoiceFromDubIRN($Data_dub_irn, $sekB64);
                            //echo $Irn_Ewaybill;
                            $irn_res = json_decode($Irn_Ewaybill, true);
                            $data_irn_res[] = $irn_res;
                            $data_irn_res = array();
                            array_push($data_irn_res, $irn_res);
                            foreach ($data_irn_res as $row) {
                                $AckNo = $row['AckNo'];
                                $AckDt = $row['AckDt'];
                                $Irn = $row['Irn'];
                                $SignedInvoice = $row['SignedInvoice'];
                                $SignedQRCode = $row['SignedQRCode'];
                                $Status = $row['Status'];
                                $EwbNo = $row['EwbNo'];
                                $EwbDt = $row['EwbDt'];
                                $EwbValidTill = $row['EwbValidTill'];
                                $Remarks = $row['Remarks'];
                                $invoice_json = json_decode($invoice_trim_right, true);
                                $invoice_number = $invoice_json['docDtls']['no'];
                                $invoice_date = $invoice_json['docDtls']['dt'];
                                $invoice_type = "Tax Invoice";
                                $invoice_value = $invoice_json['valDtls']['totInvVal'];
                                $invoice_gst = $invoice_json['buyerDtls']['gstin'];
                                $customer_name = $invoice_json['buyerDtls']['lglNm'];
                                $value_amt = $invoice_json['valDtls']['totInvVal'];
                                $query = "INSERT INTO einvoice(AckNo,AckDt,Irn,SignedInvoice,SignedQRCode,Status,EwbNo,EwbDt,EwbValidTill,Remarks,invoice_no,invoice_date,customer_gst,customer_name,value_amt) value ('$AckNo','$AckDt','$Irn','$SignedInvoice','$SignedQRCode','$Status','$EwbNo','$EwbDt','$EwbValidTill','$Remarks','$invoice_number','$invoice_date','$invoice_gst','$customer_name','$value_amt')";
                                mysqli_query($connect, $query);
                                $output = "Sl. No" . "\t" . "IRN" . "\t" . "Ack No" . "\t" . "Ack Date" . "\t" . "Doc No" . "\t" . "Doc Typ" . "\t" . "Doc Date" . "\t" . "Inv Value." . "\t" . "Recipient GSTIN" . "\t" . "Status" . "\t" . "Signed QR Code" . "\t" . "EWB No./ If Any Errors While Creating EWB." . "\n" . "1" . "\t" . "$Irn" . "\t" . "$AckNo" . "\t" . "$AckDt" . "\t" . "$invoice_number"
                                    . "\t" . "$invoice_type" . "\t" . "$invoice_date" . "\t" . "$invoice_value" . "\t" . "$invoice_gst" . "\t" . "" . "\t" . "$SignedQRCode" . "\t" . "$EwbNo" . "\n";
                                //echo $output;
                                file_put_contents("ewaybill.txt", $output . PHP_EOL, FILE_APPEND | LOCK_EX);
                            }
                        } */
                    }
                }

                //echo $query;
            }
        }
    }
    closedir($handle);
}
