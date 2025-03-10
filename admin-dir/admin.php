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
    <title>Welcome to Discount Juice!</title>
    <link rel="stylesheet" href="../default.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h1, h2 {
            color: green;
            text-align: center;
        }
        form {
            margin: 20px 0;
            text-align: center;
        }
        form input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .product-list {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .product-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-item a {
            margin-left: 10px;
            color: blue;
            text-decoration: none;
        }
        .product-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<?php include("adminheader.inc.php"); ?>

<div class="container">
    <h1>Admin Panel</h1>
    <h2>Search Products</h2>
    <form method="POST" action="admin.php">
        <input type="text" name="search" placeholder="Enter product name" />
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" /> <!-- XSS mitigation -->
        <input type="submit" value="Search" />
    </form>

    <div class="product-list">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
            // Check CSRF token
            $csrf_token = $_POST['csrf_token'] ?? null;
            if ($csrf_token !== $_SESSION['csrf_token']) {
                die("Invalid CSRF token");
            }

            // SQL injection mitigation: Use prepared statements with parameterized queries
            $search = '%' . $mysqli->real_escape_string($_POST['search']) . '%'; // SQL injection mitigation
            $stmt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY id ASC");
            $stmt->bind_param("s", $search); // SQL injection mitigation
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    // Sanitize output to prevent XSS attacks
                    echo htmlspecialchars($row["id"]) . " - " . htmlspecialchars($row["name"]) . " ($" . htmlspecialchars($row["price"]) . ")"; // XSS mitigation
                    if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
                        echo " <a href='update.php?id=" . htmlspecialchars($row['id']) . "'>Update</a>"; // XSS mitigation
                        echo " <a href='delete.php?id=" . htmlspecialchars($row['id']) . "'>Delete</a>"; // XSS mitigation
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }

            $stmt->close();
        }
        ?>
    </div>
</div>
</body>
</html>