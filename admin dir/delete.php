<?php require_once "db.inc"; ?>
<html>
<body>
<nav>
	<ul>
		<li><a href='index.html'>home</a></li>
		<li><a href='products.html'>products</a></li>
		<li><a href='login.php'>sign in</a></li>
</nav>
<?php

$myid = $_REQUEST['id'];

$sql = "DELETE FROM products WHERE id=$myid";

// This is the object-oriented style to query the database
if($mysqli->query($sql) === TRUE) {
	echo "Successfully deleted.";
} else {
	echo "Error: $sql <br>" . $mysqli->error;
}

?>

</body>
</html>
