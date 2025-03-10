<?php
session_start();
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php";

// Check if the user is logged in and is 'admin'
if (!isset($_SESSION['signed_in']) || $_SESSION['username'] !== 'admin') {
    // Redirect to the login page if not logged in or not 'admin'
    header("Location: ../login.php");
    exit();
}

// Generate a new CSRF token if it's not already set
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(64));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Product - Discount Juice Shop</title>
    <link rel="stylesheet" href="../default.css">
</head>
<body>
<?php include("adminheader.inc.php"); ?>

<div class="container">
    <h1>Update Product</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check CSRF token
        $csrf_token = $_POST['csrf_token'] ?? null;
        if ($csrf_token !== $_SESSION['csrf_token']) {
            die("Invalid CSRF token");
        }

        // Sanitize and validate user inputs
        $myid = $mysqli->real_escape_string($_POST['id']); // SQL injection mitigation
        $myname = $mysqli->real_escape_string($_POST['name']); // SQL injection mitigation
        $myprice = $mysqli->real_escape_string($_POST['price']); // SQL injection mitigation

        if (!empty($myid) && is_numeric($myid) && !empty($myname) && is_numeric($myprice)) {
            // Use prepared statements to prevent SQL injection
            $stmt = $mysqli->prepare("UPDATE products SET name=?, price=? WHERE id=?");
            $stmt->bind_param("sdi", $myname, $myprice, $myid); // SQL injection mitigation

            if ($stmt->execute()) {
                // Sanitize output to prevent XSS attacks
                echo "<p class='message'>" . htmlspecialchars($myname) . " updated successfully!</p>"; // XSS mitigation
            } else {
                // Sanitize output to prevent XSS attacks
                echo "<p class='error'>Error: " . htmlspecialchars($stmt->error) . "</p>"; // XSS mitigation
            }

            $stmt->close();
        } else {
            echo "<p class='error'>Please enter a valid ID, name, and price.</p>";
        }
    }

    if (isset($_GET['id'])) {
        // Sanitize and validate the ID parameter
        $myid = $mysqli->real_escape_string($_GET['id']); // SQL injection mitigation
        if (is_numeric($myid)) {
            // Use prepared statements to prevent SQL injection
            $stmt = $mysqli->prepare("SELECT * FROM products WHERE id=?");
            $stmt->bind_param("i", $myid); // SQL injection mitigation
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
        } else {
            echo "<p class='error'>Invalid product ID.</p>";
        }
    }
    ?>

    <form method="POST" action="update.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" /> <!-- XSS mitigation -->

        <label for="id">Product ID:</label>
        <input type="text" id="id" name="id" value="<?= htmlspecialchars($row['id']) ?>" required /> <!-- XSS mitigation -->

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']) ?>" required /> <!-- XSS mitigation -->

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($row['price']) ?>" required /> <!-- XSS mitigation -->

        <input type="submit" value="Update" />
    </form>
</div>

</body>
</html>
