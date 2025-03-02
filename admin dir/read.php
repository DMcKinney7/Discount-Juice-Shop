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

<h1>Products</h1>

<?php

$sql = "SELECT * FROM products";

// This is the procedural style to query the database
$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_array($result)) {
	echo "{$row['name']} \${$row['price']} 
		<a href='update.php?id={$row['id']}'>update</a> 
		<a href='delete.php?id={$row['id']}'>delete</a>
		<br />";
}

?>

</body>
</html>
