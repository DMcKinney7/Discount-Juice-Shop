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
    <title>Delete Product - Discount Juice Shop</title>
    <link rel="stylesheet" href="../default.css">
</head>
<body>
<?php include("adminheader.inc.php"); ?>

<div class="container">
    <h1>Delete Product</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check CSRF token
        $csrf_token = $_POST['csrf_token'] ?? null;
        if ($csrf_token !== $_SESSION['csrf_token']) {
            die("Invalid CSRF token");
        }

        if (isset($_POST['id'])) {
            // SQL injection mitigation: Use prepared statements with parameterized queries
            $myid = $mysqli->real_escape_string($_POST['id']); // SQL injection mitigation

            $stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $myid); // SQL injection mitigation

            if ($stmt->execute()) {
                echo "<p class='message'>Product successfully deleted.</p>";
            } else {
                echo "<p class='error'>Error: " . htmlspecialchars($stmt->error) . "</p>"; // XSS mitigation
            }

            $stmt->close();
        } else {
            echo "<p class='error'>No product ID provided.</p>";
        }
    }
    ?>

    <form method="POST" action="delete.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" /> <!-- XSS mitigation -->
        <label for="id">Product ID:</label>
        <input type="text" id="id" name="id" required /> <!-- XSS mitigation -->
        <input type="submit" value="Delete Product" />
    </form>
</div>

</body>
</html>
