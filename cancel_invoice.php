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

if (isset($_POST['invoice'])) {
    require_once("./cancel_run.php");
    header("location: ./cancel_invoice");
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
                <li class="active open"><a href="cancel_invoice"><i class="zmdi zmdi-delete"></i><span>Cancel E-Way Bill</span> </a></li>
                <li><a href="get_gst"><i class="zmdi zmdi-airplay"></i><span>Get GST</span> </a></li>
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
                    <h2>E Invoice Cancel
                        <!--  <small class="text-muted">Welcome to Nexa Application</small> -->
                    </h2>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <ul class="breadcrumb float-md-right">
                        <li class="breadcrumb-item"><a href="index"><i class="zmdi zmdi-home"></i> NagaDigi</a></li>
                        <li class="breadcrumb-item active">E Invoice Cancel</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2> Ready to Cancel Invoice </h2>
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
                            <div class="table-responsive">
                                <?php
                                $updatecount = 1;

                                if ($handle = opendir('./cancel')) {
                                    $output = '
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                            <tr>
                                            <th scope="col">S.NO</th>
                                            <th scope="col">Invoice No</th>
                                            <th scope="col">Customer GST</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">AckNo</th>
                                            <th scope="col">EwbNo</th>
                                            </tr>
                                            </thead>
                                            ';
                                    while (false !== ($einvoice_cancel = readdir($handle))) {
                                        if ($einvoice_cancel != "." && $einvoice_cancel != "..") {
                                            $invoice = file_get_contents("./cancel/" . $einvoice_cancel);
                                            $invoice_no = "SELECT * From einvoice where invoice_no = " . $invoice . " ORDER BY id DESC LIMIT 1";
                                            $result_cancel = mysqli_query($connect, $invoice_no);
                                            while ($row = mysqli_fetch_array($result_cancel)) {
                                                $output .= '
                                                            <tr>
                                                            <td>' . $updatecount++ . '</td>   
                                                            <td style="word-break: break-all;">' . $row['invoice_no'] . '</td>
                                                            <td style="word-break: break-all;">' . $row['customer_gst'] . '</td>
                                                            <td style="word-break: break-all;">' . $row['customer_name'] . '</td>
                                                            <td style="word-break: break-all;">' . $row['AckNo'] . '</td>
                                                            <td style="word-break: break-all;">' . $row['EwbNo'] . '</td>
                                                            </tr>
                                                            ';
                                            }
                                        }
                                    }
                                    $output .= '
                                            </table>
                                            <br />
                                            <div align="center">
                                            <ul class="pagination">
                                            ';
                                    echo $output;
                                }

                                ?>
                            </div>
                            <form method="post">
                                <button style="display: block;" class="btn  btn-raised btn-warning waves-effect" id="submit" type="submit" name="invoice" value="invoice">Cancel E-Invoice and E-way Bill</button>
                                <div><img src="assets/images/process.gif" style="width: 165px;margin-top: -45px;display: none;" id="img"></div>
                            </form>
                        </div>
                    </div>


                </div>
            </div>

    </section>
    <section class="content" style="margin-top: -10px;">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2> Cancel Invoice List </h2>
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
                            <div class="table-responsive">
                                <?php
                                $updatecount_1 = 1;

                                $query = "SELECT * FROM einvoice WHERE irn_cancel_date IS NOT NULL ORDER BY id DESC";
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
                                            <th scope="col">Eway Bill No</th>
                                            <th scope="col">Cancel Date</th>
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
                                                <td style="word-break: break-all;">' . $row['EwbNo'] . '</td>
                                                <td style="word-break: break-all;">' . $row['irn_cancel_date'] . '</td>
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

                                ?>
                            </div>
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
    <script>
        $("#submit").click(function() {
            $("#img").css("display", "block");
            $("#img").focus();
            $("#submit").css("display", "none");
            $("#submit").focus();
        });
    </script>
</body>

</html>