<?php
session_start();
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php";

// Check if the user is logged in and is 'admin'
if (!isset($_SESSION['signed_in']) || $_SESSION['username'] !== 'admin') {
    // Redirect to the login page if not logged in or not 'admin'
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Test - Discount Juice Shop</title>
    <link rel="stylesheet" href="../default.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        form {
            margin: 20px 0;
            text-align: center;
        }
        form input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: darkgreen;
        }
        .message {
            text-align: center;
            color: #27ae60;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include("header.inc.php"); ?>

<div class="container">
    <h1>PHP Test Page</h1>
    <p>This is text from HTML.</p>

    <?php
    echo "<p class='message'>PHP works</p>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $message = htmlspecialchars($_POST['message']);
        echo "<p class='message'>You entered: $message</p>";
    }
    ?>

    <form method="POST" action="phptest.php">
        <label for="message">Enter a message:</label>
        <input type="text" id="message" name="message" required />
        <input type="submit" value="Submit" />
    </form>
</div>

</body>
</html>