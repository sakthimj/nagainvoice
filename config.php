<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'einvoice');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>

<?php
$connect = mysqli_connect("localhost", "root", "", "einvoice");
if ($connect == false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
<?php
$connect1 = new PDO("mysql:host=localhost; dbname=einvoice", "root", "");
if ($connect1 == false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>