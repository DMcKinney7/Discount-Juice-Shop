<?php require_once "/var/www/html/Discount-Juice-Shop/db.inc.php"; ?>
<html>
<body>
<?php include("header.inc.php"); ?>
<?php

$myid = $_REQUEST['id'];
$myname = $_REQUEST['name'];
$myprice = $_REQUEST['price'];

if($_REQUEST['name']) {
	$sql = "UPDATE products SET name='$myname', price=$myprice WHERE id=$myid";

	// This is the procedural style to query the database
	if(mysqli_query($mysqli, $sql) === TRUE){
		echo "$myname updated successfully!";
	} else {
		echo "Error: $sql <br>" . mysqli_error($mysqli);
	}
}

$sql = "SELECT * FROM products WHERE id=$myid";

// This is the procedural style to query the database
$result = mysqli_query($mysqli, $sql);

$row = mysqli_fetch_array($result);

?>

<form>
	<input type="hidden" name="id" value="<?= $row['id'] ?>" />

	<label>Name:</label>
	<input type="text" name="name" value="<?php echo $row['name'] ?>" />

	<label>Price:</label>
	<input type="text" name="price" value="<?= $row['price'] ?>" />

	<input type="submit" value="update" />
</form>

</body>
</html>
