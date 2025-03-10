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
    <title>Products - Discount Juice Shop</title>
    <link rel="stylesheet" href="../default.css">
</head>
<body>

<h1>Products</h1>

<?php
// SQL injection mitigation: Use prepared statements with parameterized queries
$stmt = $mysqli->prepare("SELECT id, name, price FROM products");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo htmlspecialchars($row['name']) . " $" . htmlspecialchars($row['price']) . " 
        <a href='update.php?id=" . htmlspecialchars($row['id']) . "&csrf_token=" . htmlspecialchars($_SESSION['csrf_token']) . "'>update</a> 
        <a href='delete.php?id=" . htmlspecialchars($row['id']) . "&csrf_token=" . htmlspecialchars($_SESSION['csrf_token']) . "'>delete</a>
        <br />";
}

$stmt->close();
?>

</body>
</html>
