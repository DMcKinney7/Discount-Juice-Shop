<?php
// Start the user's session
session_start();

// Required our database connection
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php"; 

// Check that there are contents in the cart, otherwise redirect back to show the empty cart message
if(empty($_SESSION['cart'])) {
    header("Location: /cart/");
    exit();
}

// Form variables
$myname = $_REQUEST['name'] ?? '';
$mystreet = $_REQUEST['street'] ?? '';
$mycity = $_REQUEST['city'] ?? '';
$mystate = $_REQUEST['state'] ?? '';
$myzip = $_REQUEST['zip'] ?? '';
$mycreditcard = $_REQUEST['creditcard'] ?? '';
$myexpiration = $_REQUEST['expiration'] ?? '';
$mysecuritycode = $_REQUEST['securitycode'] ?? '';

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Disco Juice - Checkout</title>
    <link rel="stylesheet" href="/default.css">
    <style>
        .error {
            border: 1px solid red;
            color: red;
            padding: .5rem;
            width: 50rem;
            margin: 1rem auto;
        }
        th {
            text-align: right;
        }
        .container {
            width: 50rem;
            margin: auto;
        }
        table {
            width: 100%;
        }
        th, td {
            padding: .5rem;
        }
        input[type="submit"] {
            padding: .5rem 1rem;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
<?php include("../header.inc.php"); ?>

<div class="container">
    <h1>Checkout</h1>

    <?php
    // BEGIN: If-else field check
    // If ALL of the fields have been submitted, enter the order
    if (!empty($myname) && !empty($mystreet) && !empty($mycity) && !empty($myzip) && !empty($mycreditcard) && !empty($myexpiration) && !empty($mysecuritycode)) {
        // Insert the order into the database
        $sql = "INSERT INTO orders (name, street, city, state, zip, creditcard, expiration, securitycode) VALUES ('$myname', '$mystreet', '$mycity', '$mystate', '$myzip', '$mycreditcard', '$myexpiration', '$mysecuritycode')";
        mysqli_query($mysqli, $sql);
        $order_id = mysqli_insert_id($mysqli);

        // Loop through the items in the shopping cart
        $shopping_cart_total = 0;
        foreach($_SESSION['cart'] as $item_product_id => $item) {
            foreach($item as $item_price => $item_quantity) {
                $shopping_cart_total += $item_quantity * $item_price;

                // Foreach product ordered, add the product id, quantity, and price
                $sql = "INSERT INTO line_items (order_id, product_id, quantity, price) VALUES ($order_id, $item_product_id, $item_quantity, $item_price)";
                mysqli_query($mysqli, $sql);
            }
        }

        // Now that everything is entered into the database, empty the cart
        unset($_SESSION['cart']);
    ?>

        <p>Thank you for your order! Your order confirmation number is <strong><?= $order_id ?></strong>, and you have been charged <strong>$<?= number_format($shopping_cart_total,2) ?></strong>. Please allow 5-30 business days to receive it in the post.</p>
        <p><em>Just when you've forgotten about it, or decide you want a refund, it'll show up for sure! (Or just wait another day or two...)</em></p>

    <?php
    // Else not ALL of the fields have been submitted, so show the form
    } else {
        // If one or more of the fields have been submitted, display an error message
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<p class='error'>ERROR: Please complete all fields.</p>";
        }
    ?>

    <p>Please enter your billing details.</p>
    <form method="POST" action="checkout.php">
        <table>
            <tr>
                <th><label for="name">Name</label></th>
                <td><input id="name" type="text" name="name" value="<?= htmlspecialchars($myname) ?>" required /></td>
            </tr>
            <tr>
                <th><label for="street">Street</label></th>
                <td><input id="street" type="text" name="street" value="<?= htmlspecialchars($mystreet) ?>" required /></td>
            </tr>
            <tr>
                <th><label for="city">City</label></th>
                <td><input id="city" type="text" name="city" value="<?= htmlspecialchars($mycity) ?>" required /></td>
            </tr>
            <tr>
                <th><label for="state">State</label></th>
                <td>
                    <select id="state" name="state" required>
                        <option value="">Select a state</option>
                        <?php
                        $states = array(
                            'AL'=>'Alabama', 'AK'=>'Alaska', 'AZ'=>'Arizona', 'AR'=>'Arkansas', 'CA'=>'California',
                            'CO'=>'Colorado', 'CT'=>'Connecticut', 'DE'=>'Delaware', 'DC'=>'District of Columbia',
                            'FL'=>'Florida', 'GA'=>'Georgia', 'HI'=>'Hawaii', 'ID'=>'Idaho', 'IL'=>'Illinois',
                            'IN'=>'Indiana', 'IA'=>'Iowa', 'KS'=>'Kansas', 'KY'=>'Kentucky', 'LA'=>'Louisiana',
                            'ME'=>'Maine', 'MD'=>'Maryland', 'MA'=>'Massachusetts', 'MI'=>'Michigan', 'MN'=>'Minnesota',
                            'MS'=>'Mississippi', 'MO'=>'Missouri', 'MT'=>'Montana', 'NE'=>'Nebraska', 'NV'=>'Nevada',
                            'NH'=>'New Hampshire', 'NJ'=>'New Jersey', 'NM'=>'New Mexico', 'NY'=>'New York',
                            'NC'=>'North Carolina', 'ND'=>'North Dakota', 'OH'=>'Ohio', 'OK'=>'Oklahoma', 'OR'=>'Oregon',
                            'PA'=>'Pennsylvania', 'RI'=>'Rhode Island', 'SC'=>'South Carolina', 'SD'=>'South Dakota',
                            'TN'=>'Tennessee', 'TX'=>'Texas', 'UT'=>'Utah', 'VT'=>'Vermont', 'VA'=>'Virginia',
                            'WA'=>'Washington', 'WV'=>'West Virginia', 'WI'=>'Wisconsin', 'WY'=>'Wyoming',
                        );

                        foreach($states as $key => $value) {
                            echo "<option value='$key'".($mystate == $key ? " selected" : "").">$value</option>\n";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="zip">Zip</label></th>
                <td><input id="zip" type="text" name="zip" value="<?= htmlspecialchars($myzip) ?>" required /></td>
            </tr>
            <tr>
                <th><label for="creditcard">Credit Card</label></th>
                <td><input id="creditcard" type="text" name="creditcard" value="<?= htmlspecialchars($mycreditcard) ?>" required /></td>
            </tr>
            <tr>
                <th><label for="expiration">Expiration</label></th>
                <td><input id="expiration" type="month" name="expiration" value="<?= htmlspecialchars($myexpiration) ?>" required /></td>
            </tr>
            <tr>
                <th><label for="securitycode">Security Code</label></th>
                <td><input id="securitycode" type="password" name="securitycode" maxlength="4" value="<?= htmlspecialchars($mysecuritycode) ?>" required /></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Complete Purchase" /></td>
            </tr>
        </table>
    </form>

    <?php
    // END: If-else field check
    }
    ?>

</div>
</body>
</html>
