<?php
/* $key = "ao4UV83F81O05qtrH1Emx03qBqmUwjmyKEAjvzfvoMI=";
$data = "T0W0U52gPPk5+BpbbLIIHyWA+ag90RsmuSLCRqTQoTWfb0dji13f9xKOKsSmElUj";
$decryption = openssl_decrypt(base64_decode($data), "AES-256-ECB", $key, OPENSSL_RAW_DATA);
echo $decryption; */
/* function DecryptBySymmetricKey($encryptedSek, $appKey)
{
    $sek = openssl_decrypt($encryptedSek, 'aes-256-ecb', $appKey, 0);
    return base64_encode($sek);
} */

// Test
// $appKey = openssl_random_pseudo_bytes(32);
/* $appKey = 'b42b0df750c207f0288cced8d89976431c43247ba45b64bf3ef0ed9325f9fb16'; // test key for comparison with C# code
$encryptedSek = 'RwDmJm/OBNW8bVTISa7nmOuMiixp9blBM0g3S0v7h1OKZ9SMJGlg0DVpARRyLadH';
$decryptedSek = DecryptBySymmetricKey($encryptedSek, $appKey);
print($decryptedSek . PHP_EOL); */ // MDEyMzQ1Njc4OTAxMjM0NTY3ODkwMTIzNDU2Nzg5MDE=

/* unction decryptBySymmetricKey($encSekB64, $appKey)
{
    $sek = openssl_decrypt($encSekB64, "aes-256-ecb", base64_decode($appKey), 0);                  // the SEK
    $sekB64 = base64_encode($sek);                                                  // the Base64 encoded SEK
    return $sekB64;
}
function encryptBySymmetricKey($dataB64, $sekB64)
{
    $data = base64_decode($dataB64);                                                // the data to encrypt
    $sek = base64_decode($sekB64);                                                  // the SEK
    $encDataB64 = openssl_encrypt($data, "aes-256-ecb", $sek, 0);                   // the Base64 encoded ciphertext
    return $encDataB64;
}
$appKey = 'p7vRArKHQeUyiRPz6ZXvzjpcVC2Yvu6HZkkL99FtC7I=';                                       // the 32 bytes AppKey
$encSekB64 = '+2LPQaNFij/j8xyfkAYnf31d6qH/Dw1kVamEYCV5V/l104S9cWiWt6eeAl119oNB';    // the Base64 encoded encrypted SEK 
$dataB64 = 'HL4GnZJzOBUDMnKC0DOxSPk+LdXPsUEuwezRxYd2/Pk=';          // the base64 encoded data
$sekB64 = decryptBySymmetricKey($encSekB64, $appKey);                               // the Base64 encoded SEK   
$encDataB64 = encryptBySymmetricKey($dataB64, $sekB64);                             // the Base64 encoded ciphertext
echo $sekB64 . "\n";                     */                                            // zVoede7m2nnvMHcWYIfKhrvsilSFEZYiltJmxVQQnAQ=
//echo $encDataB64;

/* $data = "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";
$sekB64 = "xuzVGFAJXqVfdzmVHNI31dHQbJFEw1yxVOm1ReljI5A=";

function decryptBySymmetricKey($data_irn, $sekB64)
{
    $data = $data_irn;
    $sek = base64_decode($sekB64);
    $irn_de = openssl_decrypt($data, "aes-256-ecb", $sek, 0);
    return $irn_de;
}

$data_irn = $data;
$irn_de = decryptBySymmetricKey($data_irn, $sekB64);
echo $irn_de; */

/* if ($handle = opendir('./invoice')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

            $invoice = json_decode(file_get_contents("./invoice/" . $entry), true);
            $invoice_final = json_encode($invoice, JSON_PRETTY_PRINT);
            $invoice_trim = ltrim($invoice_final, "[");
            $invoice_trim_right = rtrim($invoice_trim, "]");
            echo ($invoice_trim_right);
        }
    }

    closedir($handle);
}
 */
/* $invoice = file_get_contents("./invoice3.json");
$invoice_trim = ltrim($invoice, "[");
$invoice_trim_right = rtrim($invoice_trim, "]");
echo $invoice_trim_right; */
?>
<?php
/* function PostRequest($url, $_data)
{
    // convert variables array to string:
    $data = array();
    while (list($n, $v) = each($_data)) {
        $data[] = "$n=$v";
    }
    $data = implode('&', $data);
    // format --> test1=a&test2=b etc.
    // parse the given URL
    $url = parse_url($url);
    if ($url['scheme'] != 'http') {
        die('Only HTTP request are supported !');
    }
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
    // open a socket connection on port 80
    $fp = fsockopen($host, 80);
    // send the request headers:
    fputs($fp, "POST $path HTTP/1.1\r\n");
    fputs($fp, "Host: $host\r\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Content-length: " . strlen($data) . "\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    fputs($fp, $data);
    $result = '';
    while (!feof($fp)) {
        // receive the results of the request
        $result .= fgets($fp, 128);
    }
    // close the socket connection:
    fclose($fp);
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
    // return as array:
    return array($header, $content);
}
$data = array(
    'apikey' => "5bcdfa4d-3943-45ac-9917-2e73629a43ae",
    'clientid' => "c2fee230-52f4-4795-b1d4-7cf4abc10c82",
    'msisdn' => "919600480947",
    'sid' => "NAGARD",
    'msg' => "Test Message",
    'fl' => "0",
    'gwid' => "2"
);
list($header, $content) = PostRequest(
    "http://sms.nettyfish.com/vendorsms/pushsms.aspx",
    $data
);
echo $content;
 */

/* $invoice = json_encode(file_get_contents("./invoice/0000000029244231.json"), true);
echo $invoice; */

/* $invoice = json_decode(file_get_contents("./invoice/0000000029244576.json"), true);
$invoice_final = json_encode($invoice, JSON_PRETTY_PRINT);
$invoice_trim = ltrim($invoice_final, "[");
$invoice_trim_right = rtrim($invoice_trim, "]");
$remove_dis = str_replace('"distance": 10', '"distance": 0', $invoice_trim_right);
echo $remove_dis; */

/* if ($handle = opendir('./invoice')) {

    while (false !== ($json_count = readdir($handle))) {
        $updatecount = 1;
        if ($json_count != "." && $json_count != "..") {
            $invoice = json_decode(file_get_contents("./invoice/" . $json_count), true);
            $invoice_final = json_encode($invoice, JSON_PRETTY_PRINT);
            //echo $invoice_final;
            $invoice_trim = ltrim($invoice_final, "[");
            $invoice_trim_right = rtrim($invoice_trim, "]");
            $data = json_decode($invoice_final, true);
            $output = '
            <table class="table table-bordered table-hover ">
            <thead class="table-primary">
            <tr>
            <th scope="col">S.NO</th>
            <th scope="col">Invoice No</th>
            <th scope="col">Customer GST</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Value</th>
            <th scope="col">Invoice Date</th>
            </tr>
            </thead>
            ';
            foreach ($data as $user) {
                $output .= '
                <tr>
                <td>' . $updatecount++ . '</td>   
                <td style="word-break: break-all;">' . $user['docDtls']['no'] . '</td>
                <td style="word-break: break-all;">' . $user['buyerDtls']['gstin'] . '</td>
                <td style="word-break: break-all;">' . $user['buyerDtls']['lglNm'] . '</td>
                <td style="word-break: break-all;">' . $user['valDtls']['totInvVal'] . '</td>
                <td style="word-break: break-all;">' . $user['docDtls']['dt'] . '</td>
                </tr>
                ';
            }
            $output .= '
            </table>
            <br />
            <div align="center">
            <ul class="pagination">
            ';
            echo $output;
        }
    }
} */



/* include_once("config.php");

$query = "SELECT * FROM einvoice ORDER BY id DESC";
$result = mysqli_query($connect, $query);
$updatecount_1 = 1;
$output = '
            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
            <tr>
            <th scope="col">S.NO</th>
            <th scope="col">Invoice No</th>
            <th scope="col">Customer GST</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Ack No</th>
            <th scope="col">Eway Bill No</th>
            </tr>
            </thead>
            ';
while ($row = mysqli_fetch_array($result)) {
    $output .= '
                <tr>
                <td>' . $updatecount_1++ . '</td>   
                <td style="word-break: break-all;">' . $row['invoice_no'] . '</td>
                <td style="word-break: break-all;">' . $row['customer_gst'] . '</td>
                <td style="word-break: break-all;">' . $row['customer_name'] . '</td>
                <td style="word-break: break-all;">' . $row['AckNo'] . '</td>
                <td style="word-break: break-all;">' . $row['EwbNo'] . '</td>
                </tr>
                ';
}
$output .= '
            </table>
            ';
echo $output;

?> */

function encryptBySymmetricKey($eway, $sekB64)
{
    $data = base64_encode($eway);
    $sek = base64_decode($sekB64);
    $encDataB64 = openssl_encrypt($data, "aes-256-ecb", $sek, 0);
    return $encDataB64;
}
$sekB64 = "4Ck1vHLKkDnIZPG7fe/hf5qMoxlZRDZ4E60pYYlTz8c=";
$eway = '{
    "ewbNo": 511008783638,
    "cancelRsnCode": 2,
    "cancelRmrk": "Cancelled the order"
  }';
echo $eway;
$eway1 = base64_encode($eway);
//echo $eway1;
$encDataB64 = encryptBySymmetricKey($eway, $sekB64);
echo $encDataB64;
