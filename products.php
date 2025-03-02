<?php require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php"; ?>
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