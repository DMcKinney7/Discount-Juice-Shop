<?php
session_start();
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php";

// Generate a new CSRF token if it's not already set
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(64));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products - Discount Juice Shop</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .logo {
            max-width: 150px;
            height: auto;
        }
        .product-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-item h3 {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }
        .product-item p {
            margin: 5px 0 0;
            color: #666;
        }
        .buy-now-form {
            margin-top: 10px;
        }
    </style>
</head>

<body>
<?php include("header.inc.php"); ?>

<div class="container">
    <a href="/"><img src="images/logo.jpg" alt="Discount Juice Shop Logo" class="logo"></a>
    <h1>Discount Juice Shop</h1>
    <h2>Our Products</h2>

    <div class="product-list">
        <?php
        $result = mysqli_query($mysqli, "SELECT * FROM products");
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                echo "<div class='product-item'>";
                echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                echo "<p>$" . htmlspecialchars($row["price"]) . "</p>";
                echo "<form class='buy-now-form' action='/Discount-Juice-Shop/cart/index.php' method='POST'>";
                // Include CSRF token for security
                echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "' />";
                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id']) . "' />";
                echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "' />";
                echo "<input type='submit' value='Buy Now' />";
                echo "</form>";
                echo "</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "<p class='error'>Error: " . htmlspecialchars(mysqli_error($mysqli)) . "</p>";
        }
        ?>
    </div>
</div>
</body>
</html>