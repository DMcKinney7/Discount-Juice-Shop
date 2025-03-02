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
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Product - Discount Juice Shop</title>
    <link rel="stylesheet" href="../default.css">
</head>
<body>
<?php include("adminheader.inc.php"); ?>

<div class="container">
    <h1>Create Product</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $myname = $mysqli->real_escape_string($_POST['name']);
        $myprice = $mysqli->real_escape_string($_POST['price']);

        if (!empty($myname) && is_numeric($myprice)) {
            $stmt = $mysqli->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
            $stmt->bind_param("sd", $myname, $myprice);

            if ($stmt->execute()) {
                echo "<p class='message'>Product $myname created successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='error'>Please enter a valid name and price.</p>";
        }
    }
    ?>

    <form method="POST" action="create.php">
        <label>Name:</label>
        <input type="text" name="name" required />

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required />

        <input type="submit" value="Create Product" />
    </form>
</div>
</body>
</html>
