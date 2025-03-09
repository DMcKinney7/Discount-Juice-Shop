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
    <title>Create Product - Discount Juice Shop</title>
    <link rel="stylesheet" href="../default.css">
</head>
<body>
<?php include("adminheader.inc.php"); ?>

<div class="container">
    <h1>Create Product</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check CSRF token
        $csrf_token = $_POST['csrf_token'] ?? null;
        if ($csrf_token !== $_SESSION['csrf_token']) {
            die("Invalid CSRF token");
        }

        // SQL injection mitigation: Use prepared statements with parameterized queries
        $myname = $mysqli->real_escape_string($_POST['name']);
        $myprice = $_POST['price'];

        if (!empty($myname) && is_numeric($myprice)) {
            $stmt = $mysqli->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
            $stmt->bind_param("sd", $myname, $myprice);

            if ($stmt->execute()) {
                echo "<p class='message'>Product " . htmlspecialchars($myname) . " created successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='error'>Please enter a valid name and price.</p>";
        }
    }
    ?>

    <form method="POST" action="create.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" />

        <label>Name:</label>
        <input type="text" name="name" required />

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required />

        <input type="submit" value="Create Product" />
    </form>
</div>
</body>
</html>
