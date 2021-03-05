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
//dashboard
date_default_timezone_set('Asia/Calcutta');
$date = date("Y-m-d");
$query1 = "SELECT * From einvoice WHERE invoice_no is not null && date LIKE '$date%'";
$result1 = mysqli_query($connect, $query1);
$today_invoice = mysqli_num_rows($result1);
$query2 = "SELECT * From einvoice WHERE EwbNo is not null && date LIKE '$date%'";
$result2 = mysqli_query($connect, $query2);
$today_einvoice = mysqli_num_rows($result2);
$query3 = "SELECT * From einvoice WHERE irn_cancel_date is not null && date LIKE '$date%'";
$result3 = mysqli_query($connect, $query3);
$today_cancel_einvoice = mysqli_num_rows($result3);
$query3 = "SELECT * From einvoice WHERE irn_cancel_date is not null && date LIKE '$date%'";
$result3 = mysqli_query($connect, $query3);
$today_cancel_einvoice = mysqli_num_rows($result3);
$query4 = "SELECT SUM(value_amt) AS Totalamit FROM einvoice WHERE date LIKE '$date%'";
$result4 = mysqli_query($connect, $query4);
$row = mysqli_fetch_array($result4);
$value_amt = $row['Totalamit'];
?>
<!doctype html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

    <title>:: NagaDigi :: Index</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" />
    <link rel="stylesheet" href="assets/plugins/morrisjs/morris.css" />
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
    <div class="overlay"></div>
    <!-- Search  -->
    <div class="search-bar">
        <div class="search-icon"> <i class="material-icons">search</i> </div>
        <input type="text" placeholder="Explore NagaDigi...">
        <div class="close-search"> <i class="material-icons">close</i> </div>
    </div>

    <!-- Top Bar -->
    <?php require_once("./assets/page/menu.php"); ?>

    <!-- Left Sidebar -->
    <?php require_once("./assets/page/left_sidebar.php"); ?>

    <!-- Right Sidebar -->

    <!-- Chat-launcher -->


    <!-- Main Content -->
    <section class="content home">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Dashboard
                        <small class="text-muted">Welcome to NagaDigi E invoice</small>
                    </h2>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <ul class="breadcrumb float-md-right">
                        <li class="breadcrumb-item"><a href="index"><i class="zmdi zmdi-home"></i> NagaDigi</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <ul class="row profile_state list-unstyled">
                            <li class="col-lg-3 col-md-4 col-6">
                                <div class="body">
                                    <i class="zmdi zmdi-store col-amber"></i>
                                    <h4><?php echo $today_invoice ?></h4>
                                    <span>Invoice Count</span>
                                </div>
                            </li>
                            <li class="col-lg-3 col-md-4 col-6">
                                <div class="body">
                                    <i class="zmdi zmdi-truck col-blue"></i>
                                    <h4><?php echo $today_einvoice ?></h4>
                                    <span>E Invoice Count</span>
                                </div>
                            </li>
                            <li class="col-lg-3 col-md-4 col-6">
                                <div class="body">
                                    <i class="zmdi zmdi-comment-text col-red"></i>
                                    <h4><?php echo $today_cancel_einvoice ?></h4>
                                    <span>Cancel Invoice Count</span>
                                </div>
                            </li>
                            <li class="col-lg-3 col-md-4 col-6">
                                <div class="body">
                                    <i class="zmdi zmdi-balance-wallet text-success"></i>
                                    <h4><?php echo "₹ " . (round($value_amt  / 100000, 0)) . " L"; ?></h4>
                                    <span>Value</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <p class="m-b-0">© 2021 NLCD Development Team</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Jquery Core Js -->
    <script src="assets/bundles/libscripts.bundle.js"></script>
    <!-- Lib Scripts Plugin Js -->
    <script src="assets/bundles/vendorscripts.bundle.js"></script>
    <!-- Lib Scripts Plugin Js -->

    <script src="assets/bundles/jvectormap.bundle.js"></script>
    <!-- JVectorMap Plugin Js -->
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <!-- Morris Plugin Js -->
    <script src="assets/bundles/sparkline.bundle.js"></script>
    <!-- Sparkline Plugin Js -->
    <script src="assets/bundles/knob.bundle.js"></script>
    <!-- Jquery Knob Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/pages/index.js"></script>
    <script src="assets/js/pages/charts/jquery-knob.min.js"></script>
</body>

</html>