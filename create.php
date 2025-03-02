<?php require_once "db.inc"; ?>
<html>
<body>

<?php

if($_REQUEST['name']) {

	$myname = $_REQUEST['name'];
	$myprice = $_REQUEST['price'];

	$sql = "INSERT INTO products (name, price) VALUES ('$myname', $myprice)";

	// This is the object-oriented style to query the database
	if($mysqli->query($sql) === TRUE) {
		echo "Product $myname created successfully!";
	} else {
		echo "Error: $sql <br>" . $mysqli->error;
	}

}

?>

<form>
	<label>Name:</label>
	<input type="text" name="name" />

	<label>Price:</label>
	<input type="text" name="price" />

	<input type="submit" value="Create Product" />
</form>

</body>
</html>
