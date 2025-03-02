<html lang=en>
<?php require_once "/var/www/html/Discount-Juice-Shop/db.inc.php"; ?>
    <head>
    <title>Welcome to Discount Juice!</title>
    <style>
    h2 {
        color: green;
    }
    </style>
    <link rel="stylesheet" href="default.css">
</head>

<body>
<?php include("header.inc.php"); ?>
<form>
    <label>Name:</label>
    <input type="text" name="search" />

    <input type="submit" value="search"/>
</form>

<?php
$search = $_GET['search'];

$sql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY name ASC";

$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_assoc($result)) {
    echo $row["name"]. " ($". $row["price"]. ")";
    if(isset($_SESSION['username'])) {
        echo "<a href='update.php?id=". $row['id']. "'>update</a>
        <a href='delete.php?id=". $row['id']. "'>delete</a>";
    }
    echo "<br />
     ------------
     <br />";
}
?>
</body>

</html>