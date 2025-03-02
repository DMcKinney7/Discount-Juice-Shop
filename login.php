<?php
session_start();
require_once "/var/www/html/Discount-Juice-Shop/Connections/db.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Discount Juice Shop Log-In Portal</title>
    <link rel="stylesheet" href="default.css">
</head>
<body>
<?php include("header.inc.php"); ?>

<div class="container">
    <h1>Log In</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $myusername = htmlspecialchars($mysqli->real_escape_string($_POST['username']));
        $mypassword = htmlspecialchars($mysqli->real_escape_string($_POST['password']));

        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=? AND password=SHA2(?, 256)");
        $stmt->bind_param("ss", $myusername, $mypassword);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // This is what happens when a user successfully authenticates
        if (!empty($row)) {
            // Delete any data in the current session to start new
            session_destroy();
            session_start();

            $_SESSION['username'] = $row['username'];
            $_SESSION['signed_in'] = true;

            // Redirect to the index page
            echo "<p class='message'>You have successfully logged in. Redirecting...</p>";
            header("refresh:2;url=index.php");
            exit();
        } else {
            echo "<p class='error'>Incorrect username or password</p>";
        }

        $stmt->close();
    }
    ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="redirect" value="index.php" />

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required />

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required />

        <input type="submit" value="Log In" />
    </form>
</div>

</body>
</html>
