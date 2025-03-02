<!DOCTYPE html>
<html lang="en">
<?php require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php"; ?>
<head>
    <title>Welcome to Discount Juice!</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .logo {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
<!-- the style of these pages are brought to you by github ai: I dont like html or css but the ai does -->
<?php include("header.inc.php"); ?>

<div class="container">
    <a href="/"><img src="images/logo.jpg" alt="Discount Juice Shop Logo" class="logo"></a>
    <h1>Welcome to Discount Juice!</h1>
    <h2>Search for Products</h2>
    <form method="GET" action="index.php">
        <label for="search">Name:</label>
        <input type="text" id="search" name="search" placeholder="Enter product name" />
        <input type="submit" value="Search" />
    </form>

    <div class="product-list">
        <?php
        if (isset($_GET['search'])) {
            $search = $mysqli->real_escape_string($_GET['search']);

            $sql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY name ASC";

            $result = mysqli_query($mysqli, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product-item'>";
                    echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                    echo "<p>$" . htmlspecialchars($row["price"]) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p class='message'>No products found.</p>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>