<?php
session_start();
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php";

// Generate a new CSRF token on each request
$_SESSION["csrf_token"] = bin2hex(random_bytes(64));

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form variables
    $myproduct_id = $_POST['product_id'] ?? null;
    $myquantity = $_POST['quantity'] ?? 1;
    $csrf_token = $_POST['csrf_token'] ?? null;

    // Check CSRF token
    if ($csrf_token !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    // Validate quantity
    $myquantity = (is_numeric($myquantity) && $myquantity > 0) ? $myquantity : 1;

    // Get the price from the database
    if (!empty($myproduct_id)) {
        // Prepare the statement to get the price from the database
        $stmt = $mysqli->prepare("SELECT price FROM products WHERE id=?");
        $stmt->bind_param("i", $myproduct_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $myprice = $row['price'];
        $stmt->close();

        // If the user sent a product_id, add the quantity to the existing cart quantity
        if (!empty($myprice)) {
            if (!isset($_SESSION['cart'][$myproduct_id])) {
                $_SESSION['cart'][$myproduct_id] = [];
            }
            if (!isset($_SESSION['cart'][$myproduct_id][$myprice])) {
                $_SESSION['cart'][$myproduct_id][$myprice] = 0;
            }
            $_SESSION['cart'][$myproduct_id][$myprice] += $myquantity;
        }
    }
}

// Select all of the product details from the database
$sql = "SELECT * FROM products";
$results = mysqli_query($mysqli, $sql);
$product_name = [];
while ($row = mysqli_fetch_array($results)) {
    $product_name[$row['id']] = $row['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
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
    <p>CSRF Token: <?= htmlspecialchars($_SESSION["csrf_token"]) ?></p>
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
            // Sanitize user input to prevent SQL injection
            $search = $mysqli->real_escape_string($_GET['search']);

            // Use prepared statements to prevent SQL injection
            $stmt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY name ASC");
            $search_param = "%$search%";
            $stmt->bind_param("s", $search_param);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    // Sanitize output to prevent XSS attacks
                    echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                    echo "<p>$" . htmlspecialchars($row["price"]) . "</p>";
                    echo "<form class='buy-now-form' action='/Discount-Juice-Shop/cart/index.php' method='POST'>";
                    // Include CSRF token for security
                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id']) . "' />";
                    echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "' />";
                    echo "<label for='quantity'>Quantity:</label>";
                    // Add quantity input with validation
                    echo "<input type='number' id='quantity' name='quantity' min='1' max='5' value='1' />";
                    echo "<input type='submit' value='Buy Now' />";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p class='message'>No products found.</p>";
            }

            $stmt->close();
        }
        ?>
    </div>
</div>
</body>
</html>