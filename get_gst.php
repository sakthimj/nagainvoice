<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: sign-in");
    exit;
    // Store data in session variables
    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $id;
    $_SESSION["username"] = $username;
}
require_once "config.php";
$username = $_SESSION['username'];
$sql_users = "SELECT * from users WHERE username = '" . $username . "' ";
$result = mysqli_query($connect, $sql_users);
$row = mysqli_fetch_array($result);
$email = $row['email'];
function decryptBySymmetricKey($encSekB64, $appKey)
{
    $sek = openssl_decrypt($encSekB64, "aes-256-ecb", base64_decode($appKey), 0);
    $sekB64 = base64_encode($sek);
    return $sekB64;
}
function decryptBySymmetricKeyi($gst_data, $sekB64)
{
    $data = $gst_data;
    $sek = base64_decode($sekB64);
    $irn_de = openssl_decrypt($data, "aes-256-ecb", $sek, 0);
    return $irn_de;
}
if (count($_POST) > 0) {
    $data = "Naga$2020";
    $key = file_get_contents("./einv_sandbox.pem");
    openssl_public_encrypt($data, $output, $key);
    $de = base64_encode($output);
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
    $Sek = $data['Data']['Sek'];
    $AuthToken = $data['Data']['AuthToken'];
    $appKey = 'p7vRArKHQeUyiRPz6ZXvzjpcVC2Yvu6HZkkL99FtC7I=';
    $encSekB64 = $Sek;
    $sekB64 = decryptBySymmetricKey($encSekB64, $appKey);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://einv-apisandbox.nic.in/eivital/v1.03/Master/gstin/' . $_POST['gst_number'],
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
            "AuthToken: $AuthToken"
        ),
    ));

    $gst = curl_exec($curl);
    curl_close($curl);
    $gst_de = json_decode($gst, true);
    $gst_data = $gst_de['Data'];
    $gst_details = json_decode(decryptBySymmetricKeyi($gst_data, $sekB64), true);
    $gst_num = $gst_details['Gstin'];
    echo $gst_num;
}
?>
<!doctype html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: NagaDigi :: E Invoice</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Favicon-->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- JQuery DataTable Css -->
    <link rel="stylesheet" href="assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css">
    <!-- Custom Css -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/color_skins.css">
</head>

<body class="theme-orange">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <p>Please wait...</p>
            <div class="m-t-30"><img src="assets/images/logo.svg" width="48" height="48" alt="Nexa"></div>
        </div>
    </div>

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div><!-- Search  -->
    <div class="search-bar">
        <div class="search-icon"> <i class="material-icons">search</i> </div>
        <input type="text" placeholder="Explore NagaDigi...">
        <div class="close-search"> <i class="material-icons">close</i> </div>
    </div>

    <!-- Top Bar -->
    <?php require_once("./assets/page/menu.php"); ?>

    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="assets/images/xs/avatar1.jpg" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown"><?php echo $_SESSION["username"]; ?> </div>
                <!-- <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="button"> keyboard_arrow_down </i>
                <ul class="dropdown-menu slideUp">
                    <li><a href="profile.html"><i class="material-icons">person</i>Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                    <li class="divider"></li>
                    <li><a href="sign-in.html"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div> -->
                <div class="email"><?php echo $email; ?></div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <li><a href="index"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
                <li><a href="einvoice"><i class="zmdi zmdi-upload"></i><span>E-Way Bill Genarete</span> </a></li>
                <li><a href="cancel_invoice"><i class="zmdi zmdi-delete"></i><span>Cancel E-Way Bill</span> </a></li>
                <li class="active open"><a href="get_gst"><i class="zmdi zmdi-airplay"></i><span>Get GST</span> </a></li>
                <li><a href="get_irn"><i class="zmdi zmdi-shopping-cart"></i><span>IRN Details</span> </a></li>

            </ul>
        </div>
        <!-- #Menu -->
    </aside>

    <!-- Right Sidebar -->

    <section class="content">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Get GST Details
                        <!--  <small class="text-muted">Welcome to Nexa Application</small> -->
                    </h2>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <ul class="breadcrumb float-md-right">
                        <li class="breadcrumb-item"><a href="index"><i class="zmdi zmdi-home"></i> NagaDigi</a></li>
                        <li class="breadcrumb-item active">Get GST Details</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2> GST Form </h2>
                            <!-- <ul class="header-dropdown">
                                <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more-vert"></i> </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul> -->
                        </div>
                        <div class="body">
                            <form id="form_advanced_validation" method="POST">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="gst_number" maxlength="15" minlength="15" required>
                                        <label class="form-label">GST Number</label>
                                    </div>
                                    <div class="help-info">Enter the GST Number</div>
                                </div>
                                <button class="btn btn-raised btn-primary waves-effect" type="submit" value="submit">SUBMIT</button>
                            </form>
                        </div>
                        <div class="body">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>GST Number</th>
                                        <th>TradeName</th>
                                        <th>LegalName</th>
                                        <th>Address</th>
                                        <th>StateCode</th>
                                        <th>Status</th>
                                        <th>TxpType</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php if (isset($gst_details['Gstin'])) {
                                                echo $gst_details['Gstin'];
                                            } else {
                                                echo "-";
                                            }  ?></td>
                                        <td><?php if (isset($gst_details['TradeName'])) {
                                                echo $gst_details['TradeName'];
                                            } else {
                                                echo "-";
                                            }  ?></td>
                                        <td><?php if (isset($gst_details['LegalName'])) {
                                                echo $gst_details['LegalName'];
                                            } else {
                                                echo "-";
                                            }  ?></td>
                                        <td><?php if (isset($gst_details['AddrBnm'])) {
                                                echo $gst_details['AddrBnm'] . "\n" . $gst_details['AddrBno'] . "," . $gst_details['AddrFlno'] . "\n" . $gst_details['AddrSt'] . "\n" . $gst_details['AddrLoc'] . "-" . $gst_details['AddrPncd'];
                                            } else {
                                                echo "-";
                                            }  ?></td>
                                        <td><?php if (isset($gst_details['StateCode'])) {
                                                echo $gst_details['StateCode'];
                                            } else {
                                                echo "-";
                                            }  ?> </td>
                                        <td><?php if (isset($gst_details['Status'])) {
                                                echo $gst_details['Status'];
                                            } else {
                                                echo "-";
                                            }  ?> </td>
                                        <td><?php if (isset($gst_details['TxpType'])) {
                                                echo $gst_details['TxpType'];
                                            } else {
                                                echo "-";
                                            }  ?> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>

    </section>


    <!-- Jquery Core Js -->
    <script src="assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js -->
    <script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js -->

    <!-- Jquery DataTable Plugin Js -->
    <script src="assets/bundles/datatablescripts.bundle.js"></script>
    <script src="assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="assets/plugins/jquery-datatable/buttons/buttons.flash.min.js"></script>
    <script src="assets/plugins/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="assets/plugins/jquery-datatable/buttons/buttons.print.min.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script><!-- Custom Js -->
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
</body>

</html>