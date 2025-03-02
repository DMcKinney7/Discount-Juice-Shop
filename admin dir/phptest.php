<?php
session_start();
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php";

// Check if the user is logged in and is 'bitstudent'
if (!isset($_SESSION['signed_in']) || $_SESSION['username'] !== 'bitstudent') {
    // Redirect to the login page if not logged in or not 'bitstudent'
    header("Location: /login.php");
    exit();
}
?>
<html>
<body>
<?php include("header.inc.php"); ?>
<p>This is text from HTML.</p>
<?php


echo "php works";
?>
</body>
</html>