<?php include("header.inc.php"); ?>
<?php
require_once "/var/www/html/Discount-Juice-Shop/db.inc.php";

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the URL parameter
$searchTerm = $_GET['search'];

// Prepare the SQL query using prepared statements
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY name");
$searchTerm = "%$searchTerm%";
$stmt->bind_param("s", $searchTerm);

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Check if any results are found
    if ($result->num_rows > 0) {
        // Output the search results
        while ($row = $result->fetch_assoc()) {
            echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
            // Output other product details as needed
        }
    } else {
        echo "No results found.";
    }

    // Free result set
    $result->free();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Discount Juice Shop - Search</title>
</head>
<body>
</body>
</html>
