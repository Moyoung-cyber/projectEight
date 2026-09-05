<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "<script>console.log('Connected Successfully')</script>";
}

$server_query = "create database shop";
$run = mysqli_query($conn, $server_query);

if (!$run) {
    echo "Error  creating database: " . mysqli_error($conn);
} else {
    echo "Database created successfully.";
}

// Add this SQL to create the reviews table if it does not exist
$create_reviews_table = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    rating INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_reviews_table);
?>